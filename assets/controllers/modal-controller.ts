import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';
import { FrameElement, TurboBeforeFetchResponseEvent, visit } from '@hotwired/turbo';
import { FileUtils } from '@assets/common';

export default class extends Controller<HTMLElement> {
  static targets = ['modal', 'frame', 'title'];

  static values = {
    formUrl: String
  };

  declare readonly modalTarget: HTMLDivElement;
  declare readonly frameTarget: FrameElement;
  declare readonly titleTarget: HTMLHeadingElement;

  private modal: Modal = null;

  connect = (): void => {
    document.addEventListener('turbo:before-fetch-response', this.beforeFetchResponse);
  };

  disconnect = (): void => {
    document.removeEventListener('turbo:before-fetch-response', this.beforeFetchResponse);
  };

  openModal = async ({
    params: { src, title }
  }: {
    params: { src?: string; title?: string };
  }): Promise<void> => {
    if (src !== null) {
      this.frameTarget.src = src;
    }

    if (title !== null) {
      this.titleTarget.innerText = title;
    }

    if (!this.modal) {
      this.modal = new Modal(this.modalTarget);
    }

    await this.frameTarget.reload();
    this.modal.show();
  };

  closeModal = (): void => {
    this.modal.hide();
  };

  private beforeFetchResponse = (event: TurboBeforeFetchResponseEvent): void => {
    if (!this.modal || !this.modal._isShown) {
      return;
    }

    const fetchResponse = event.detail.fetchResponse;
    const responseHeaders = fetchResponse.response.headers;
    const hasDownloadFile = responseHeaders.has('content-disposition');

    if (fetchResponse.succeeded) {
      event.preventDefault();
      this.modal.hide();

      if (hasDownloadFile) {
        const filename = responseHeaders
          .get('content-disposition')
          .split('filename=')[1]
          .split(';')[0];

        fetchResponse.response
          .blob()
          .then(blob => FileUtils.downloadFile(blob, filename))
          .catch(error => console.log(error));
      }

      if (fetchResponse.redirected) {
        visit(fetchResponse.location);
      }
    }
  };
}
