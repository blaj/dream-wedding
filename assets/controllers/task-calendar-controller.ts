import listPlugin from '@fullcalendar/list';
import {CalendarController} from "./common";

export default class extends CalendarController {

    protected plugins = [listPlugin];
    protected initialView = 'listWeek';
}