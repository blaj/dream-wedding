import { localeFallbacks } from '../var/translations/configuration';
import { trans, setLocaleFallbacks } from '@symfony/ux-translator';

setLocaleFallbacks(localeFallbacks);

export { trans };

export * from '../var/translations';
