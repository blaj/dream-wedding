import { RenameController } from './common';

export default class extends RenameController {
  get updateNameRoute(): string {
    return 'wedding_table_ajax_update_name';
  }
}
