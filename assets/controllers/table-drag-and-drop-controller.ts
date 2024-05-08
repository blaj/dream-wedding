import { DragAndDropController } from './common';

export default class extends DragAndDropController {
  get idAttribute(): string {
    return 'data-guest-id';
  }

  get updateGroupRoute(): string {
    return 'wedding_guest_ajax_update_table';
  }

  get updateOrderNoRoute(): string {
    return 'wedding_guest_ajax_update_table_order_no';
  }

  get emptyRowColspan(): number {
    return 3;
  }
}
