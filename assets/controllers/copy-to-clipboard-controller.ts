import { Controller } from '@hotwired/stimulus';
import { Tooltip } from 'bootstrap';
import { trans } from '../translator';
import { COPIED } from '../../var/translations';

export default class extends Controller<HTMLElement> {
  static values = {
    toCopy: String
  };

  declare readonly toCopyValue: string;

  copy = async (): Promise<void> => {
    await navigator.clipboard.writeText(this.toCopyValue);

    const tooltip = new Tooltip(this.element, { title: trans(COPIED), trigger: 'manual' });
    tooltip.show();

    setTimeout(() => {
      tooltip.hide();
      setTimeout(() => tooltip.dispose(), 500);
    }, 2000);
  };
}
