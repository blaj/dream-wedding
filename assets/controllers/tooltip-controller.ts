import { Controller } from '@hotwired/stimulus';
import { Tooltip } from 'bootstrap';

export default class extends Controller<HTMLElement> {
  private tooltip: Tooltip;

  initialize = (): void => {
    this.tooltip = new Tooltip(this.element);
  };

  disconnect = (): void => {
    if (this.tooltip == null) {
      return;
    }

    this.tooltip.dispose();
  };
}
