import { Plugin } from '@ckeditor/ckeditor5-core';
import { EventInfo } from '@ckeditor/ckeditor5-utils';
import { Differ, DiffItemInsert, DiffItemRemove } from '@ckeditor/ckeditor5-engine';
import axios from 'axios';

interface EventSource {
  differ: Differ;
}

export class SymfonyDeleteImagePlugin extends Plugin {
  init = (): void => {
    const editor = this.editor;
    const model = editor.model;

    const elementTypes = ['image', 'imageBlock', 'inlineImage'];

    model.document.on('change:data', (event: EventInfo) => {
      const source = event.source as EventSource;
      const differ = source.differ;

      if (differ.isEmpty) {
        return;
      }

      const changes = differ.getChanges({ includeChangesInGraveyard: true });

      if (changes.length === 0) {
        return;
      }

      let hasNoImageRemoved = true;

      for (let i = 0; i < changes.length; i++) {
        const change = changes[i];
        if (change && change.type === 'remove' && elementTypes.includes(change.name)) {
          hasNoImageRemoved = false;
          break;
        }
      }

      if (hasNoImageRemoved) {
        return;
      }

      const removedNodes = changes.filter(
        change => change.type === 'insert' && elementTypes.includes(change.name)
      );

      removedNodes.forEach((node: DiffItemInsert | DiffItemRemove) => {
        const removedNode = node.position.nodeAfter;
        const source = removedNode.getAttribute('src');

        axios
          .delete<void>(this.editor.config.get('deleteImage.url'), {
            data: { path: source }
          })
          .catch(() => {
            alert('Wystąpił błąd wewnętrzny serwera. Skontaktuj się z pomocą.');
          });
      });
    });
  };
}
