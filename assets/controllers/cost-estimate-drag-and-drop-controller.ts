import { DragAndDropController } from './common';

export default class extends DragAndDropController {
  get idAttribute(): string {
    return 'data-cost-estimate-id';
  }

  get updateGroupRoute(): string {
    return 'wedding_cost_estimate_ajax_update_group';
  }

  get updateOrderNoRoute(): string {
    return 'wedding_cost_estimate_ajax_update_order_no';
  }

  get emptyRowColspan(): number {
    return 8;
  }
}
