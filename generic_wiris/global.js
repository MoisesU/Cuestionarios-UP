import Core from './core/src/core.src.js';
import Parser from './core/src/parser.js';
import Util from './core/src/util.js';
import Image from './core/src/image.js';
import Configuration from './core/src/configuration.js';
import Listeners from './core/src/listeners';
import backwardsLib from './core/src/backwardslib.js';
import polyfills from './core/src/polyfills.js';
import IntegrationModel from './core/src/integrationmodel.js';
import Latex from './core/src/latex';
import GenericIntegration from './wirisplugin-generic.src.js';

// Expose WirisPlugin variable to the window.
window.WirisPlugin = {
    Core: Core,
    Parser: Parser,
    Image: Image,
    Util: Util,
    Configuration: Configuration,
    Listeners: Listeners,
    IntegrationModel: IntegrationModel,
    Latex: Latex,
    GenericIntegration: GenericIntegration
}