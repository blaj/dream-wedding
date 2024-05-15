import { RenameController } from './common';

export default class extends RenameController {
  get updateNameRoute(): string {
    return 'wedding_cost_estimate_group_ajax_update_name';
  }
}
