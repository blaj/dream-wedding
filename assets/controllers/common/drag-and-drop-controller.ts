import { Controller } from '@hotwired/stimulus';
import Sortable, { SortableEvent } from 'sortablejs';
import axios from 'axios';
import Routing from 'fos-router';
import { TemplateUtils } from '@assets/common';

export abstract class DragAndDropController extends Controller<HTMLElement> {
  static values = { weddingId: Number, locale: String };

  declare readonly weddingIdValue: number;
  declare readonly localeValue: number;

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
        Routing.generate(this.updateGroupRoute, {
          id: parseInt(event.item.getAttribute(this.idAttribute)),
          weddingId: this.weddingIdValue,
          _locale: this.localeValue
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

    event.from.innerHTML = TemplateUtils.emptyListRow(this.emptyRowColspan);
  };

  private onUpdate = (event: SortableEvent): void => {
    axios
      .put(
        Routing.generate(this.updateOrderNoRoute, {
          id: parseInt(event.item.getAttribute(this.idAttribute)),
          weddingId: this.weddingIdValue,
          _locale: this.localeValue
        }),
        { orderNo: event.newIndex }
      )
      .catch(() => {
        alert('Wystąpił błąd wewnętrzny serwera. Skontaktuj się z pomocą.');
      });
  };

  abstract get updateGroupRoute(): string;
  abstract get updateOrderNoRoute(): string;
  abstract get idAttribute(): string;
  abstract get emptyRowColspan(): number;
}
