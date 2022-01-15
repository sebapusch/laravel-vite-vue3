export default function (key) {
    let trans = key;

    ['locale', 'fallback_locale'].find(locale => {
        try {
            const t = key.split('.').reduce((o, i) => o[i], __trans[locale]);
            if (typeof t === 'string') {
                trans = t;
                return true;
            }
        } catch (_) {}
    });

    return trans;
}
