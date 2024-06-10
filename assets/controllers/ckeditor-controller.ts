import { Controller } from '@hotwired/stimulus';
import { ClassicEditor } from '@ckeditor/ckeditor5-editor-classic';
import { Alignment } from '@ckeditor/ckeditor5-alignment';
import { Autoformat } from '@ckeditor/ckeditor5-autoformat';
import { Bold, Italic } from '@ckeditor/ckeditor5-basic-styles';
import { BlockQuote } from '@ckeditor/ckeditor5-block-quote';
import { CloudServices } from '@ckeditor/ckeditor5-cloud-services';
import { Essentials } from '@ckeditor/ckeditor5-essentials';
import { Heading } from '@ckeditor/ckeditor5-heading';
import {
  Image,
  ImageCaption,
  ImageStyle,
  ImageToolbar,
  ImageUpload
} from '@ckeditor/ckeditor5-image';
import { Indent } from '@ckeditor/ckeditor5-indent';
import { Link, LinkImage } from '@ckeditor/ckeditor5-link';
import { List } from '@ckeditor/ckeditor5-list';
import { MediaEmbed } from '@ckeditor/ckeditor5-media-embed';
import { Paragraph } from '@ckeditor/ckeditor5-paragraph';
import { PasteFromOffice } from '@ckeditor/ckeditor5-paste-from-office';
import { Table, TableToolbar } from '@ckeditor/ckeditor5-table';
import { TextTransformation } from '@ckeditor/ckeditor5-typing';
import { SymfonyDeleteImagePlugin, SymfonyUploadAdapterPlugin } from '@assets/common';
import Routing from 'fos-router';

export default class extends Controller<HTMLTextAreaElement> {
  static values = {
    locale: String
  };

  declare readonly localeValue: string;

  connect = (): void => {
    ClassicEditor.create(this.element, {
      plugins: [
        Alignment,
        Autoformat,
        BlockQuote,
        Bold,
        CloudServices,
        Essentials,
        Heading,
        Image,
        ImageCaption,
        ImageStyle,
        ImageToolbar,
        ImageUpload,
        Indent,
        Italic,
        Link,
        LinkImage,
        List,
        MediaEmbed,
        Paragraph,
        PasteFromOffice,
        Table,
        TableToolbar,
        TextTransformation,
        SymfonyDeleteImagePlugin
      ],
      extraPlugins: [SymfonyUploadAdapterPlugin],
      toolbar: {
        items: [
          'alignment',
          'heading',
          '|',
          'bold',
          'italic',
          'link',
          'bulletedList',
          'numberedList',
          '|',
          'outdent',
          'indent',
          '|',
          'blockQuote',
          'insertTable',
          'insertImage',
          'mediaEmbed',
          'undo',
          'redo'
        ]
      },
      language: 'en',
      image: {
        toolbar: [
          'imageStyle:block',
          'imageStyle:side',
          '|',
          'toggleImageCaption',
          'imageTextAlternative',
          '|',
          'linkImage'
        ]
      },
      table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
      },
      uploadImage: {
        url: Routing.generate('app_post_ajax_upload_image', { _locale: this.localeValue })
      },
      deleteImage: {
        url: Routing.generate('app_post_ajax_delete_image', { _locale: this.localeValue })
      }
    });
  };
}
