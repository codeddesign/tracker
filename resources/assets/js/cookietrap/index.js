import sync from './_sync';
import { visit } from './_tracker';

sync.then(function(value) {
    visit();
});
