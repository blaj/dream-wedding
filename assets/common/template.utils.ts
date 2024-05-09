import { trans } from '../translator';
import { EMPTY_LIST } from '../../var/translations';

export const emptyListRow = (emptyRowColspan: number): string =>
  `` +
  `<tr class="text-center empty-list-row">` +
  `  <td colspan="${emptyRowColspan}">` +
  `    <i class="bi bi-card-list"></i>` +
  `` +
  `    ${trans(EMPTY_LIST)}` +
  `  </td>` +
  `</tr>`;

export const hide = (element: HTMLElement): void => {
  element.classList.add('d-none');
  element.classList.remove('d-block');
};

export const show = (element: HTMLElement): void => {
  element.classList.add('d-block');
  element.classList.remove('d-none');
};
