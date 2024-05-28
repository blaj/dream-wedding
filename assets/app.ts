import 'overlayscrollbars/styles/overlayscrollbars.css';
import './scss/style.scss';
import { startStimulusApp } from '@symfony/stimulus-bridge';
import Routing from 'fos-router';

Routing.setRoutingData(require('./routes.json'));

export const app = startStimulusApp(
  require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
  )
);
