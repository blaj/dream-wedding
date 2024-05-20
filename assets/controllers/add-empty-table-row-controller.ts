import { Controller } from '@hotwired/stimulus';

export default class extends Controller<HTMLTableElement> {
  static targets = ['row', 'buttonRow', 'tbodyContainer'];

  declare readonly rowTargets: HTMLTableRowElement[];
  declare readonly buttonRowTarget: HTMLTableRowElement;
  declare readonly tbodyContainerTarget: HTMLTableElement;

  private clonedRow: HTMLTableRowElement;
  private index: number;

  connect = (): void => {
    this.clonedRow = this.rowTargets[this.rowTargets.length - 1].cloneNode(
      true
    ) as HTMLTableRowElement;
    this.index = this.rowTargets.length - 1;
  };

  removeRow = (event: Event): void => {
    const element = event.target as HTMLElement;

    let row = element.parentNode.parentNode as HTMLTableRowElement;

    if (!(element instanceof HTMLButtonElement)) {
      row = element.parentNode.parentNode.parentNode as HTMLTableRowElement;
    }

    this.tbodyContainerTarget.removeChild(row);
  };

  addEmptyRow = (): void => {
    this.index++;

    const clonedRow = this.clonedRow.cloneNode(true) as HTMLTableRowElement;
    const inputs = clonedRow.querySelectorAll('input');
    const selects = clonedRow.querySelectorAll('select');

    inputs.forEach((input: HTMLInputElement) => {
      this.changeInputNameAndId(input);
      input.value = '';
    });

    selects.forEach((select: HTMLSelectElement) => this.changeInputNameAndId(select));

    this.buttonRowTarget.before(clonedRow);
  };

  private changeInputNameAndId = (element: HTMLInputElement | HTMLSelectElement): void => {
    element.id = element.id.replace(/\d+(\.\d+)?/g, this.index.toString());
    element.name = element.name.replace(/\[\d+(\.\d+)?\]/g, `[${this.index.toString()}]`);
  };
}
