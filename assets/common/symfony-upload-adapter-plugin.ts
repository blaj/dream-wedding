import { ClassicEditor } from '@ckeditor/ckeditor5-editor-classic';
import { FileLoader, UploadAdapter, UploadResponse } from '@ckeditor/ckeditor5-upload';

class SymfonyUploadAdapter implements UploadAdapter {
  private xhr: XMLHttpRequest;

  constructor(
    private readonly loader: FileLoader,
    private readonly editor: ClassicEditor
  ) {}

  abort(): void {}

  upload = (): Promise<UploadResponse> =>
    this.loader.file.then(
      file =>
        new Promise((resolve, reject) => {
          this.initRequest();
          this.initListeners(resolve, reject, file);
          this.sendRequest(file);
        })
    );

  private initRequest = (): void => {
    this.xhr = new XMLHttpRequest();
    this.xhr.open('POST', this.editor.config.get('uploadImage.url'), true);
    this.xhr.responseType = 'json';
  };

  private initListeners = (
    resolve: (value: UploadResponse | PromiseLike<UploadResponse>) => void,
    reject: any,
    file: File
  ): void => {
    const xhr = this.xhr;
    const loader = this.loader;
    const genericErrorText = `Couldn't upload file: ${file.name}.`;

    xhr.addEventListener('error', () => reject(genericErrorText));
    xhr.addEventListener('abort', () => reject());
    xhr.addEventListener('load', () => {
      const response = xhr.response;

      if (!response || response.error) {
        return reject(response && response.error ? response.error.message : genericErrorText);
      }

      resolve({
        default: response.url
      });
    });

    if (xhr.upload) {
      xhr.upload.addEventListener('progress', evt => {
        if (evt.lengthComputable) {
          loader.uploadTotal = evt.total;
          loader.uploaded = evt.loaded;
        }
      });
    }
  };

  private sendRequest = (file: File): void => {
    const data = new FormData();
    data.append('file', file);
    this.xhr.send(data);
  };
}

export function SymfonyUploadAdapterPlugin(editor: ClassicEditor) {
  editor.plugins.get('FileRepository').createUploadAdapter = (loader: FileLoader) => {
    return new SymfonyUploadAdapter(loader, editor);
  };
}
