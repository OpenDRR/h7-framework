(function() {
  var animate, animate_scroll, at, iframe_template, scroll_each, scroll_to, smoothstep, top, write_iframe;

  animate_scroll = false;

  at = Array;

  top = function(el) {
    return el[0].getBoundingClientRect().top;
  };

  smoothstep = function(t) {
    return t * t * t * (t * (t * 6 - 15) + 10);
  };

  describe("sticky columns", function() {
    ["inline-block", "float"].forEach((function(_this) {
      return function(type) {
        return describe(type, function() {
          [["right stick, default align", "<div class=\"stick_columns " + type + "\">\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n  <div class=\"cell stick_cell\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>"], ["left stick, default align", "<div class=\"stick_columns " + type + "\">\n  <div class=\"cell stick_cell\"></div>\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>"], ["right stick, right align", "<div class=\"stick_columns " + type + " align_right\">\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n  <div class=\"cell stick_cell\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>"], ["left stick, right align", "<div class=\"stick_columns " + type + " align_right\">\n  <div class=\"cell stick_cell\"></div>\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>"]].forEach((function(_this) {
            return function(arg) {
              var html, name;
              name = arg[0], html = arg[1];
              return it(name, function(done) {
                return write_iframe(html).then((function(_this) {
                  return function(f) {
                    var cell;
                    cell = f.find(".stick_cell");
                    expect(top(cell)).toBe(2);
                    expect(cell.css("position")).toBe("static");
                    expect(cell.is(".is_stuck")).toBe(false);
                    return scroll_each(f, done, [
                      at(1, function() {
                        expect(top(cell)).toBe(1);
                        expect(cell.css("position")).toBe("static");
                        return expect(cell.is(".is_stuck")).toBe(false);
                      }), at(200, function() {
                        expect(top(cell)).toBe(0);
                        expect(cell.css("position")).toBe("fixed");
                        expect(cell.css("top")).toBe("0px");
                        return expect(cell.is(".is_stuck")).toBe(true);
                      }), at(480, function() {
                        expect(top(cell)).toBe(-18);
                        expect(cell.css("position")).toBe("absolute");
                        return expect(cell.is(".is_stuck")).toBe(true);
                      }), at(200, function() {
                        expect(top(cell)).toBe(0);
                        expect(cell.css("position")).toBe("fixed");
                        expect(cell.css("top")).toBe("0px");
                        return expect(cell.is(".is_stuck")).toBe(true);
                      }), at(0, function() {
                        expect(top(cell)).toBe(2);
                        expect(cell.css("position")).toBe("static");
                        return expect(cell.is(".is_stuck")).toBe(false);
                      })
                    ]);
                  };
                })(this));
              });
            };
          })(this));
          it("multiple", function(done) {
            return write_iframe("<div class=\"stick_columns " + type + "\">\n  <div class=\"cell stick_cell a\"></div>\n  <div class=\"cell stick_cell b\" style=\"height: 75vh\"></div>\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>").then((function(_this) {
              return function(f) {
                var a, b;
                a = f.find(".stick_cell.a");
                b = f.find(".stick_cell.b");
                return scroll_each(f, done, [
                  at(40, function() {
                    return [a, b].forEach(function(el) {
                      expect(top(el)).toBe(0);
                      expect(el.css("position")).toBe("fixed");
                      return expect(el.css("top")).toBe("0px");
                    });
                  }), at(430, function() {
                    expect(top(a)).toBe(0);
                    expect(a.css("position")).toBe("fixed");
                    expect(a.css("top")).toBe("0px");
                    expect(top(b)).toBe(-3);
                    expect(b.css("position")).toBe("absolute");
                    return expect(b.css("bottom")).toBe("0px");
                  }), at(485, function() {
                    [a, b].forEach(function(el) {
                      expect(el.css("position")).toBe("absolute");
                      return expect(el.css("bottom")).toBe("0px");
                    });
                    expect(top(a)).toBe(-23);
                    return expect(top(b)).toBe(-58);
                  }), at(440, function() {
                    expect(top(a)).toBe(0);
                    expect(a.css("position")).toBe("fixed");
                    expect(a.css("top")).toBe("0px");
                    expect(top(b)).toBe(-13);
                    expect(b.css("position")).toBe("absolute");
                    return expect(b.css("bottom")).toBe("0px");
                  }), at(0, function() {
                    return [a, b].forEach(function(el) {
                      expect(top(el)).toBe(2);
                      expect(el.css("position")).toBe("static");
                      return expect(el.css("top")).toBe("auto");
                    });
                  })
                ]);
              };
            })(this));
          });
          it("padding, margin, border", function(done) {
            return write_iframe("<div class=\"stick_columns " + type + "\" style=\"border-width: 8px; padding: 8px; margin: 8px; background: rgba(255,0,0,0.1); margin-bottom: 500px\">\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n  <div class=\"cell stick_cell\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>").then((function(_this) {
              return function(f) {
                var cell;
                cell = f.find(".stick_cell");
                expect(top(cell)).toBe(24);
                expect(cell.css("position")).toBe("static");
                expect(cell.is(".is_stuck")).toBe(false);
                return scroll_each(f, done, [
                  at(5, function() {
                    expect(top(cell)).toBe(19);
                    expect(cell.css("position")).toBe("static");
                    return expect(cell.is(".is_stuck")).toBe(false);
                  }), at(200, function() {
                    expect(top(cell)).toBe(0);
                    expect(cell.css("position")).toBe("fixed");
                    expect(cell.css("top")).toBe("0px");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(490, function() {
                    expect(top(cell)).toBe(-6);
                    expect(cell.css("position")).toBe("absolute");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(200, function() {
                    expect(top(cell)).toBe(0);
                    expect(cell.css("position")).toBe("fixed");
                    expect(cell.css("top")).toBe("0px");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(0, function() {
                    expect(top(cell)).toBe(24);
                    expect(cell.css("position")).toBe("static");
                    return expect(cell.is(".is_stuck")).toBe(false);
                  })
                ]);
              };
            })(this));
          });
          it("inner scrolling", function(done) {
            return write_iframe("<div class=\"stick_columns " + type + "\">\n  <div class=\"cell stick_cell\" style=\"height: 200px\"></div>\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>").then((function(_this) {
              return function(f) {
                var cell;
                cell = f.find(".stick_cell");
                expect(top(cell)).toBe(2);
                expect(cell.css("position")).toBe("static");
                expect(cell.is(".is_stuck")).toBe(false);
                return scroll_each(f, done, [
                  at(50, function() {
                    expect(top(cell)).toBe(0);
                    expect(cell.css("position")).toBe("fixed");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(170, function() {
                    expect(top(cell)).toBe(-100);
                    expect(cell.css("position")).toBe("fixed");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(410, function() {
                    expect(top(cell)).toBe(-108);
                    expect(cell.css("position")).toBe("absolute");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(350, function() {
                    expect(top(cell)).toBe(-40);
                    expect(cell.css("position")).toBe("fixed");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(230, function() {
                    expect(top(cell)).toBe(0);
                    expect(cell.css("position")).toBe("fixed");
                    return expect(cell.is(".is_stuck")).toBe(true);
                  }), at(0, function() {
                    expect(top(cell)).toBe(2);
                    expect(cell.css("position")).toBe("static");
                    return expect(cell.is(".is_stuck")).toBe(false);
                  })
                ]);
              };
            })(this));
          });
          return it("recalc", function(done) {
            return write_iframe("<div class=\"stick_columns " + type + "\">\n  <div class=\"cell static_cell\" style=\"height: 500px\"></div>\n  <div class=\"cell stick_cell\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n  window.sticky_kit_recalc = function() {\n    $(document.body).trigger(\"sticky_kit:recalc\")\n  }\n</script>").then((function(_this) {
              return function(f) {
                var cell, tall;
                cell = f.find(".stick_cell");
                tall = f.find(".static_cell");
                return scroll_to(f, 125, function() {
                  tall.css({
                    height: "150vh"
                  });
                  expect(top(cell)).toBe(0);
                  expect(cell.css("position")).toBe("fixed");
                  expect(cell.css("top")).toBe("0px");
                  cell.trigger("sticky_kit:recalc");
                  expect(top(cell)).toBe(-13);
                  expect(cell.css("position")).toBe("absolute");
                  expect(cell.css("bottom")).toBe("0px");
                  return done();
                });
              };
            })(this));
          });
        });
      };
    })(this));
    describe("flexbox", function() {

      /*
      it "right stick", (done) ->
        write_iframe("""
          <div class="stick_columns flexbox">
            <div class="cell static_cell" style="height: 500px"></div>
            <div class="cell stick_cell"></div>
          </div>
          <script type="text/javascript">
            $(".stick_cell").stick_in_parent();
          </script>
        """).then (f) =>
          expect(1).toBe 1
          done()
       */
    });
    describe("header", function() {
      it("sticks a header row", function(done) {
        return write_iframe("<div class=\"stick_header\">\n  <div class=\"stick_cell header\"></div>\n  <div class=\"stick_body\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>").then((function(_this) {
          return function(f) {
            var cell;
            cell = f.find(".stick_cell");
            expect(top(cell)).toBe(2);
            expect(cell.css("position")).toBe("static");
            expect(cell.css("top")).toBe("auto");
            expect(cell.is(".is_stuck")).toBe(false);
            return scroll_each(f, done, [
              at(20, function() {
                expect(top(cell)).toBe(0);
                expect(cell.css("position")).toBe("fixed");
                expect(cell.css("top")).toBe("0px");
                return expect(cell.is(".is_stuck")).toBe(true);
              }), at(90, function() {
                expect(top(cell)).toBe(0);
                expect(cell.css("position")).toBe("fixed");
                expect(cell.css("top")).toBe("0px");
                return expect(cell.is(".is_stuck")).toBe(true);
              }), at(125, function() {
                expect(top(cell)).toBe(-23);
                expect(cell.css("position")).toBe("absolute");
                expect(cell.css("top")).toBe("100px");
                expect(cell.css("bottom")).toBe("0px");
                return expect(cell.is(".is_stuck")).toBe(true);
              }), at(0, function() {
                expect(top(cell)).toBe(2);
                expect(cell.css("position")).toBe("static");
                expect(cell.css("top")).toBe("auto");
                return expect(cell.is(".is_stuck")).toBe(false);
              })
            ]);
          };
        })(this));
      });
      return it("sticks with custom spacer", function(done) {
        return write_iframe("<div class=\"stick_header\">\n  <div class=\"spacer\">\n    <div class=\"stick_cell header\"></div>\n  </div>\n  <div class=\"stick_body\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent({\n    parent: \".stick_header\",\n    spacer: \".spacer\",\n  })\n</script>").then((function(_this) {
          return function(f) {
            var cell;
            cell = f.find(".stick_cell");
            expect(top(cell)).toBe(7);
            expect(cell.css("position")).toBe("static");
            expect(cell.css("top")).toBe("auto");
            expect(cell.is(".is_stuck")).toBe(false);
            return scroll_each(f, done, [
              at(20, function() {
                expect(top(cell)).toBe(0);
                expect(cell.css("position")).toBe("fixed");
                expect(cell.css("top")).toBe("0px");
                return expect(cell.is(".is_stuck")).toBe(true);
              }), at(100, function() {
                expect(top(cell)).toBe(0);
                expect(cell.css("position")).toBe("fixed");
                expect(cell.css("top")).toBe("0px");
                return expect(cell.is(".is_stuck")).toBe(true);
              }), at(125, function() {
                expect(top(cell)).toBe(-13);
                expect(cell.css("position")).toBe("absolute");
                expect(cell.css("bottom")).toBe("0px");
                return expect(cell.is(".is_stuck")).toBe(true);
              })
            ]);
          };
        })(this));
      });
    });
    describe("options", function() {
      it("uses custom sticky class", function(done) {
        return write_iframe("<div class=\"stick_header\">\n  <div class=\"stick_cell header\"></div>\n  <div class=\"stick_body\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent({sticky_class: \"really_stick\"})\n</script>\n").then(function(f) {
          var cell;
          cell = f.find(".stick_cell");
          return scroll_each(f, done, [
            at(20, (function(_this) {
              return function() {
                return expect(cell.is(".really_stick")).toBe(true);
              };
            })(this))
          ]);
        });
      });
      it("disables bottoming", function(done) {
        return write_iframe("<div class=\"stick_header\">\n  <div class=\"stick_cell header\"></div>\n  <div class=\"stick_body\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent({bottoming: false})\n</script>\n").then(function(f) {
          var cell;
          cell = f.find(".stick_cell");
          return scroll_each(f, done, [
            at(2, (function(_this) {
              return function() {
                expect(top(cell)).toBe(0);
                return expect(cell.is(".is_stuck")).toBe(false);
              };
            })(this)), at(62, (function(_this) {
              return function() {
                expect(top(cell)).toBe(0);
                return expect(cell.is(".is_stuck")).toBe(true);
              };
            })(this)), at(180, (function(_this) {
              return function() {
                expect(top(cell)).toBe(0);
                return expect(cell.is(".is_stuck")).toBe(true);
              };
            })(this)), at(62, (function(_this) {
              return function() {
                expect(top(cell)).toBe(0);
                return expect(cell.is(".is_stuck")).toBe(true);
              };
            })(this)), at(0, (function(_this) {
              return function() {
                expect(top(cell)).toBe(2);
                return expect(cell.is(".is_stuck")).toBe(false);
              };
            })(this))
          ]);
        });
      });
      return it("uses offset top", function(done) {
        return write_iframe("<div class=\"stick_header\" style=\"margin-top: 20px\">\n  <div class=\"stick_cell header\"></div>\n  <div class=\"stick_body\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent({offset_top: 10})\n</script>\n").then(function(f) {
          var cell;
          cell = f.find(".stick_cell");
          return scroll_each(f, done, [
            at(5, (function(_this) {
              return function() {
                expect(top(cell)).toBe(17);
                return expect(cell.is(".is_stuck")).toBe(false);
              };
            })(this)), at(15, (function(_this) {
              return function() {
                expect(top(cell)).toBe(10);
                return expect(cell.is(".is_stuck")).toBe(true);
              };
            })(this)), at(40, (function(_this) {
              return function() {
                expect(top(cell)).toBe(10);
                return expect(cell.is(".is_stuck")).toBe(true);
              };
            })(this)), at(125, (function(_this) {
              return function() {
                expect(top(cell)).toBe(-3);
                return expect(cell.is(".is_stuck")).toBe(true);
              };
            })(this)), at(15, (function(_this) {
              return function() {
                expect(top(cell)).toBe(10);
                return expect(cell.is(".is_stuck")).toBe(true);
              };
            })(this)), at(0, (function(_this) {
              return function() {
                expect(top(cell)).toBe(22);
                return expect(cell.is(".is_stuck")).toBe(false);
              };
            })(this))
          ]);
        });
      });
    });
    return describe("events", function() {
      return it("detects events when scrolling sticky header", function(done) {
        return write_iframe("<div class=\"stick_header\">\n  <div class=\"stick_cell header\"></div>\n  <div class=\"stick_body\"></div>\n</div>\n<script type=\"text/javascript\">\n  $(\".stick_cell\").stick_in_parent()\n</script>").then((function(_this) {
          return function(f) {
            var cell, event_log;
            cell = f.find(".stick_cell");
            event_log = [];
            f.on("sticky_kit:stick", function(e) {
              return event_log.push("stick");
            });
            f.on("sticky_kit:unstick", function(e) {
              return event_log.push("unstick");
            });
            f.on("sticky_kit:bottom", function(e) {
              return event_log.push("bottom");
            });
            f.on("sticky_kit:unbottom", function(e) {
              return event_log.push("unbottom");
            });
            return scroll_each(f, done, [
              at(1, function() {
                return expect(event_log).toEqual([]);
              }), at(20, function() {
                return expect(event_log).toEqual(["stick"]);
              }), at(1, function() {
                expect(event_log).toEqual(["stick", "unstick"]);
                return event_log = [];
              }), at(20, function() {
                return expect(event_log).toEqual(["stick"]);
              }), at(90, function() {
                return expect(event_log).toEqual(["stick"]);
              }), at(125, function() {
                return expect(event_log).toEqual(["stick", "bottom"]);
              }), at(145, function() {
                return expect(event_log).toEqual(["stick", "bottom"]);
              }), at(20, function() {
                return expect(event_log).toEqual(["stick", "bottom", "unbottom"]);
              }), at(0, function() {
                return expect(event_log).toEqual(["stick", "bottom", "unbottom", "unstick"]);
              })
            ]);
          };
        })(this));
      });
    });
  });

  iframe_template = function(content) {
    return "<!DOCTYPE html>\n<html>\n<head>\n  <meta charset=\"utf-8\">\n  <script src=\"../node_modules/jquery/dist/jquery.js\"></script>\n  <script src=\"sticky-kit.js\"></script>\n\n  <style type=\"text/css\">\n    body {\n      margin: 0;\n      padding: 0;\n    }\n\n    .stick_columns {\n      border: 2px solid red;\n      margin-bottom: 100%;\n    }\n\n    .stick_columns .cell {\n      margin-right: 5px;\n      width: 40px;\n      height: 40px;\n      box-shadow: inset 0 0 0 4px rgba(255,255,255,0.5);\n      background: blue;\n      background-image: linear-gradient(0deg, rgba(255, 255, 255, .2) 50%, transparent 50%, transparent);\n      background-size: 20px 20px;\n    }\n\n    /* inline block */\n    .stick_columns.inline-block .cell {\n      vertical-align: top;\n      display: inline-block;\n    }\n\n    .stick_columns.inline-block.align_right {\n      text-align: right;\n    }\n\n    /* float */\n    .stick_columns.float {\n      overflow: hidden;\n    }\n\n    .stick_columns.float .cell {\n      float: left;\n    }\n\n    .stick_columns.float.align_right .cell {\n      float: right;\n    }\n\n\n    /* flexbox */\n    .stick_columns.flexbox {\n      display: flex;\n      align-items: flex-start;\n    }\n\n    /* header */\n    .stick_header {\n      border: 2px solid red;\n      margin-bottom: 100%;\n    }\n\n    .stick_header .stick_body {\n      background: rgba(255,0,0,0.1);\n      min-height: 100vh;\n    }\n\n    .stick_header .spacer {\n      border: 5px solid green;\n    }\n\n    .stick_header .header {\n      height: 40px;\n      box-shadow: inset 0 0 0 4px rgba(255,255,255,0.5);\n      background: blue;\n      background-image: linear-gradient(0deg, rgba(255, 255, 255, .2) 50%, transparent 50%, transparent);\n      background-size: 20px 20px;\n    }\n  </style>\n</head>\n<body>\n" + content + "\n</body>\n</html>";
  };

  write_iframe = function(contents, opts) {
    var drop, frame, height, out, width;
    if (opts == null) {
      opts = {};
    }
    width = opts.width, height = opts.height;
    if (width == null) {
      width = 200;
    }
    if (height == null) {
      height = 100;
    }
    drop = $(".iframe_drop");
    drop.html("");
    frame = $("<iframe></iframe>");
    frame.css({
      width: width + "px",
      height: height + "px"
    });
    frame.appendTo(drop);
    frame = frame[0];
    out = $.Deferred((function(_this) {
      return function(d) {
        return frame.onload = function() {
          contents = $(frame).contents();
          contents = frame.contentWindow.$(contents);
          return d.resolve(contents, frame);
        };
      };
    })(this));
    frame.contentWindow.document.open();
    frame.contentWindow.onerror = (function(_this) {
      return function(e) {
        return expect(e).toBe(null);
      };
    })(this);
    frame.contentWindow.document.write(iframe_template(contents));
    frame.contentWindow.document.close();
    return out;
  };

  animate = function(a, b, duration, callback) {
    var start, tick;
    start = window.performance.now();
    tick = function() {
      return window.requestAnimationFrame(function() {
        var now, p;
        now = window.performance.now();
        p = smoothstep(Math.min(1, (now - start) / duration));
        callback(p * (b - a) + a, p);
        if (p < 1) {
          return tick();
        }
      });
    };
    return tick();
  };

  scroll_to = function(f, position, callback) {
    var space, start_position, win;
    if (animate_scroll) {
      start_position = f.scrollTop();
      space = Math.abs(position - start_position);
      return animate(start_position, position, space, function(scroll, p) {
        f.scrollTop(Math.floor(scroll));
        if (p === 1) {
          return setTimeout(function() {
            return callback();
          }, 50);
        }
      });
    } else {
      win = f[0].defaultView;
      $(win).one("scroll", (function(_this) {
        return function() {
          return callback();
        };
      })(this));
      return f.scrollTop(position);
    }
  };

  scroll_each = function(f, done, points) {
    var scroll_to_next;
    scroll_to_next = function() {
      var next;
      next = points.shift();
      if (next) {
        return scroll_to(f, next[0], function() {
          if (typeof next[1] === "function") {
            next[1]();
          }
          return scroll_to_next();
        });
      } else {
        return done();
      }
    };
    return scroll_to_next();
  };

}).call(this);
