import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['input', 'output'];

  declare readonly inputTarget: HTMLInputElement;
  declare readonly outputTarget: HTMLImageElement;

  connect = (): void => {
    this.loadPreview();
  };

  preview = (): void => {
    this.loadPreview();
  };

  private loadPreview = (): void => {
    const input = this.inputTarget;
    const output = this.outputTarget;

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function () {
        output.src = reader.result as string;
      };

      reader.readAsDataURL(input.files[0]);
    }
  };
}
