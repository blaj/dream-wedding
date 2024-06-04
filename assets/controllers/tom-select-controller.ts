import { Controller } from '@hotwired/stimulus';
import TomSelect from 'tom-select';

export default class extends Controller<HTMLSelectElement> {
  private tomSelect: TomSelect;

  initialize = (): void => {
    this.tomSelect = new TomSelect(this.element, {
      create: false,
      persist: false,
      plugins: {
        remove_button: {
          title: 'Remove this item'
        }
      }
    });
  };

  disconnect = (): void => {
    this.tomSelect.destroy();
  };
}
