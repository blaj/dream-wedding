import { CalendarController } from './common';
import dayGridPlugin from "@fullcalendar/daygrid";

export default class extends CalendarController {

    protected plugins = [
        dayGridPlugin
    ]
}
