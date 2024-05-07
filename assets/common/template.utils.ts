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
