import { DeleteImageConfig, UploadImageConfig } from '@assets/common';

declare module '@ckeditor/ckeditor5-core' {
  interface EditorConfig {
    uploadImage: UploadImageConfig;
    deleteImage: DeleteImageConfig;
  }
}
