# smooth scroll

Simple smooth scroll plugin

© Phil Evans

## files

- smooth-scroll.js
- smooth-scroll.min.js

## current version

2.0

## dependencies

- jQuery

## usage

#### initialize

``` javascript
$(document).smooth_scroll({
  viewport: $('html, body'),
  auto_init: false,
  classes: {
    init: 'smooth-scroll',
    link: 'smooth-scroll-link'
  },
  animation: {
    speed: 500,
    ease: 'swing'
  },
  offset: 0,
  cancel: true,
  complete: null,
  debug: false
})
```

#### add smooth scroll class to links

```html
<a href="#anchor" class="smooth-scroll">Link</a>
```

#### change settings with data attributes

```html
<a href="#anchor" class="smooth-scroll" data-speed="5000">Link</a>
```

## changelog

#### 2.0

- rewrite

#### 1.1.1

- replaced complete function with promise/then
- added 'cancel' setting to turn on/off animation cancelling on mouse interaction

#### 1.1

- console logging setting
