import { Controller } from '@hotwired/stimulus';
import Sortable, { SortableEvent } from 'sortablejs';
import axios from 'axios';
import Routing from 'fos-router';
import { TemplateUtils } from '@assets/common';

export default class extends Controller<HTMLElement> {
  static values = { weddingId: Number };

  declare readonly weddingIdValue: number;

  private sortable: Sortable;

  connect = (): void => {
    this.sortable = Sortable.create(this.element, {
      group: 'shared',
      handle: '.handle',
      filter: '.empty-list-row',
      onAdd: this.onAdd,
      onRemove: this.onRemove,
      onUpdate: this.onUpdate
    });
  };

  disconnect = (): void => {
    this.sortable.destroy();
  };

  private onAdd = (event: SortableEvent): void => {
    axios
      .put(
        Routing.generate('wedding_task_ajax_update_group', {
          id: parseInt(event.item.getAttribute('data-task-id')),
          weddingId: this.weddingIdValue
        }),
        { groupId: parseInt(event.to.getAttribute('data-group-id')) }
      )
      .then(() => {
        const emptyListRow = event.to.querySelector('.empty-list-row');

        if (emptyListRow == null) {
          return;
        }

        event.to.removeChild(emptyListRow);
      })
      .catch(() => {
        alert('Wystąpił błąd wewnętrzny serwera. Skontaktuj się z pomocą.');
      });
  };

  private onRemove = (event: SortableEvent): void => {
    if (event.from.children.length > 0) {
      return;
    }

    event.from.innerHTML = TemplateUtils.emptyListRow(5);
  };

  private onUpdate = (event: SortableEvent): void => {
    axios
      .put(
        Routing.generate('wedding_task_ajax_update_order_no', {
          id: parseInt(event.item.getAttribute('data-task-id')),
          weddingId: this.weddingIdValue
        }),
        { orderNo: event.newIndex }
      )
      .catch(() => {
        alert('Wystąpił błąd wewnętrzny serwera. Skontaktuj się z pomocą.');
      });
  };
}
