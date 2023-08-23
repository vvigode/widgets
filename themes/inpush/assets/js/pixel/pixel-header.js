let send_tracking_data = (data) => {
  /* Check if we should send the analytics or not */
  if (
    data.subtype &&
    ["impression", "click", "hover"].includes(data.subtype) &&
    !pixel_analytics
  ) {
    return;
  }

  /* Append the url */
  data["url"] = window.location.href;

  try {
    navigator.sendBeacon(
      `${pixel_url_base}pixel-track/${pixel_key}`,
      JSON.stringify(data)
    );
  } catch (error) {
    console.log(`Smoorf pixel: ${error}`);
  }
};

/* Helpers */
let get_scroll_percentage = () => {
  let h = document.documentElement;
  let b = document.body;
  let st = "scrollTop";
  let sh = "scrollHeight";

  return ((h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight)) * 100;
};

/* InPush Notify Class */
class InPushManager {
  /* Create and initiate the class with the proper parameters */
  constructor(options) {
    /* Initiate the main options variable */
    this.options = {};

    /* Process the passed options and the default ones */
    this.options.content = options.content || "";
    this.options.should_show =
      typeof options.should_show === "undefined" ? true : options.should_show;
    this.options.delay =
      typeof options.delay === "undefined" ? 3000 : options.delay;
    this.options.duration =
      typeof options.duration === "undefined" ? 3000 : options.duration;
    this.options.selector = options.selector;
    this.options.url = options.url;
    this.options.url_new_tab =
      typeof options.url_new_tab === "undefined" ? true : options.url_new_tab;
    this.options.close =
      typeof options.close === "undefined" ? false : options.close;
    this.options.stop_on_focus = true;
    this.options.position =
      typeof options.position === "undefined"
        ? "bottom_left"
        : options.position;

    /* On what pages to show the notification */
    this.options.trigger_all_pages =
      typeof options.trigger_all_pages === "undefined"
        ? true
        : options.trigger_all_pages;
    this.options.triggers = options.triggers || [];

    /* More checks on if it should be displayed */
    this.options.display_frequency =
      typeof options.display_frequency === "undefined"
        ? "all_time"
        : options.display_frequency;
    this.options.display_mobile =
      typeof options.display_mobile === "undefined"
        ? true
        : options.display_mobile;
    this.options.display_desktop =
      typeof options.display_desktop === "undefined"
        ? true
        : options.display_desktop;

    /* When to show the notifications */
    this.options.display_trigger =
      typeof options.display_trigger === "undefined"
        ? "delay"
        : options.display_trigger;
    this.options.display_trigger_value =
      typeof options.display_trigger_value === "undefined"
        ? 3
        : options.display_trigger_value;

    /* On what pages to show the notification */
    this.options.data_trigger_auto =
      typeof options.data_trigger_auto === "undefined"
        ? false
        : options.data_trigger_auto;
    this.options.data_triggers_auto = options.data_triggers_auto || [];

    /* Animations */
    this.options.on_animation =
      typeof options.on_animation === "undefined"
        ? "fadeIn"
        : options.on_animation;
    this.options.off_animation =
      typeof options.off_animation === "undefined"
        ? "fadeOut"
        : options.off_animation;

    /* Must be set from the outside */
    this.options.notification_id = options.notification_id || false;
  }

  /* Function to build the toast element */
  build() {
    /* Even if we do not build / show the notification, we must check for auto recording of data. */
    if (this.options.data_trigger_auto) {
      let triggered = this.is_page_triggered(this.options.data_triggers_auto);

      if (triggered) {
        /* Make sure to know all of the form submissions on the page */
        document.querySelectorAll("form").forEach((form_element) => {
          if (
            form_element.getAttribute(
              `data-${pixel_key}-${this.options.notification_id}-form`
            )
          ) {
            return;
          }

          form_element.addEventListener("submit", (event) => {
            /* Store data from the form */
            let data = {};

            /* Parse all the input fields */
            form_element.querySelectorAll("input").forEach((input_element) => {
              if (
                input_element.type == "password" ||
                input_element.type == "hidden"
              ) {
                return;
              }

              if (input_element.name.indexOf("captcha") !== -1) {
                return;
              }

              data[`form_${input_element.name}`] = input_element.value;
            });

            /* Data collection from the form */
            send_tracking_data({
              ...data,
              notification_id: this.options.notification_id,
              type: "auto_capture",
            });
          });

          form_element.setAttribute(
            `data-${pixel_key}-${this.options.notification_id}-form`,
            true
          );
        });
      }
    }

    /* Check the should_show option: used when conversions on a notification already happened and the notification should not pop up again */
    if (!this.options.should_show) {
      return false;
    }

    /* Triggers handler ( Determine if the notification will trigger or not */
    if (!this.options.trigger_all_pages) {
      let triggered = this.is_page_triggered(this.options.triggers);

      if (!triggered) {
        return false;
      }
    }

    /* Display frequency handle */
    switch (this.options.display_frequency) {
      case "all_time":
        /* no extra conditions */
        break;

      case "once_per_session":
        if (
          sessionStorage.getItem(
            `notification_display_frequency_${this.options.notification_id}`
          )
        ) {
          return false;
        }
        break;

      case "once_per_browser":
        if (
          localStorage.getItem(
            `notification_display_frequency_${this.options.notification_id}`
          )
        ) {
          return false;
        }
        break;
    }

    /* Check if it should be shown on the current screen */
    if (
      (!this.options.display_mobile && window.innerWidth < 768) ||
      (!this.options.display_desktop && window.innerWidth > 768)
    ) {
      return false;
    }

    /* Create the html element */
    let main_element = document.createElement("div");
    main_element.className = "inpush";

    /* Positioning of the toast class */
    main_element.className += ` inpush-${this.options.position}`;

    /* Add the positioning key to the data attribute for later usage */
    main_element.setAttribute("data-position", this.options.position);

    /* Add the animation settings to the data attribute for later usage */
    main_element.setAttribute("data-on-animation", this.options.on_animation);
    main_element.setAttribute("data-off-animation", this.options.off_animation);

    /* Add the notification id to the data attribute for later usage */
    main_element.setAttribute(
      "data-notification-id",
      this.options.notification_id
    );

    /* Add the content to the element */
    main_element.innerHTML = this.options.content;
console.log(this.options);
    /* Add the close button icon if needed */
    if (this.options.close) {
      /* Create a span for close element */
      let close_button = main_element.querySelector(
        'button[class="inpush-close"]'
      );

      close_button.innerHTML = "&#10006;";

      /* Click to remove handler */
      close_button.addEventListener("click", (event) => {
        event.stopPropagation();

        /* Remove function call */
        this.constructor.remove_notification(main_element);
      });
    } else {
      main_element.querySelector('button[class="inpush-close"]').innerHTML = "";
    }

    /* Enable click on the notification if url is defined */
    if (typeof this.options.url !== "undefined" && this.options.url !== "") {
      /* Add the css class to make the toast clickable with a pointer */
      main_element.className += " inpush-clickable";

      main_element.addEventListener("click", (event) => {
        if (this.options.notification_id) {
          /* Click statistics */
          send_tracking_data({
            notification_id: this.options.notification_id,
            type: "notification",
            subtype: "click",
          });
        }

        if (this.options.url_new_tab) {
          window.open(this.options.url, "_blank");
        } else {
          window.location = this.options.url;
        }

        event.stopPropagation();
      });
    }

    /* Add event listener for clicking the branding */
    let inpush_site = main_element.querySelector(".inpush-site");

    if (inpush_site) {
      inpush_site.addEventListener("click", (event) => {
        let url = event.currentTarget.href;

        window.open(url, "_blank");

        event.stopPropagation();
        event.preventDefault();
      });
    }

    return main_element;
  }

  /* Function to make sure that the content of the site has loaded before building beginning the main process */
  initiate(callbacks = {}) {
    let wait_for_css_and_process = () => {
      let interval = null;

      interval = setInterval(() => {
        if (pixel_css_loaded) {
          clearInterval(interval);

          this.process(callbacks);
        }
      }, 100);
    };

    if (
      document.readyState === "complete" ||
      (document.readyState !== "loading" && !document.documentElement.doScroll)
    ) {
      wait_for_css_and_process();
    } else {
      document.addEventListener("DOMContentLoaded", () => {
        wait_for_css_and_process();
      });
    }

    /* Check for url changes for ajax based contents that change the url dynamically */
    let current_page = location.href;

    setInterval(() => {
      if (current_page != location.href) {
        current_page = location.href;

        /* Make sure to remove all the existing notifications */
        let toast = document.querySelector(
          `[data-notification-id="${this.options.notification_id}"]`
        );

        this.constructor.remove_notification(toast);

        wait_for_css_and_process();
      }
    }, 750);
  }

  /* Display main function */
  process(callbacks = {}) {
    let main_element = this.build();

    /* Make sure we have an element to display */
    if (!main_element) return false;

    /* Insert the element to the body depending on the position it needs to be shown */
    switch (this.options.position) {
      case "top":
      case "top_floating":
        document.body.prepend(main_element);
        break;

      case "bottom":
      case "bottom_floating":
        document.body.appendChild(main_element);
        break;

      /* Fixed positions */
      default:
        document.body.appendChild(main_element);
        break;
    }

    let display = () => {
      /* Make sure they are visible */
      main_element.style.display = "block";

      /* Add the fade in class */
      main_element.className += ` on-${this.options.on_animation}`;

      /* Handle the positioning on the screen */
      this.constructor.reposition();

      /* Run the callback if needed */
      if (callbacks.displayed) {
        callbacks.displayed(main_element);
      }

      /* Add timeout to remove the toast if needed */
      if (this.options.duration !== -1) {
        main_element.timeout = window.setTimeout(() => {
          this.constructor.remove_notification(main_element);
        }, this.options.duration);
      }

      /* Clear timeout if the user focused on the notification in certain conditions */
      if (this.options.stop_on_focus && this.options.duration !== -1) {
        /* Stop countdown on mouseover the notification */
        main_element.addEventListener("mouseover", (event) => {
          window.clearTimeout(main_element.timeout);
        });

        /* Add the timeout counter again */
        main_element.addEventListener("mouseleave", () => {
          main_element.timeout = window.setTimeout(() => {
            this.constructor.remove_notification(main_element);
          }, this.options.duration);
        });
      }

      /* Display frequency handle */
      switch (this.options.display_frequency) {
        case "all_time":
          /* no extra conditions */
          break;

        case "once_per_session":
          /* Add the notification to the session to avoid other displays on the session */
          sessionStorage.setItem(
            `notification_display_frequency_${this.options.notification_id}`,
            true
          );
          break;

        case "once_per_browser":
          /* Add the notification to the session to avoid other displays on the session */
          localStorage.setItem(
            `notification_display_frequency_${this.options.notification_id}`,
            true
          );
          break;
      }

      /* Statistics events */
      if (this.options.notification_id) {
        /* Impression notification */
        send_tracking_data({
          notification_id: this.options.notification_id,
          type: "notification",
          subtype: "impression",
        });

        /* Mouse over notification */
        main_element.addEventListener("mouseover", () => {
          /* Make sure that we didnt already send this data on the user session */
          if (
            !sessionStorage.getItem(
              `notification_hover_${this.options.notification_id}`
            )
          ) {
            send_tracking_data({
              notification_id: this.options.notification_id,
              type: "notification",
              subtype: "hover",
            });

            /* Make sure to set the sessionStorage to avoid sending this data again in this session */
            sessionStorage.setItem(
              `notification_hover_${this.options.notification_id}`,
              true
            );
          }
        });
      }

      /* Add handler for window resizing */
      window.removeEventListener("resize", this.constructor.reposition);
      window.addEventListener("resize", this.constructor.reposition);
    };

    /* Displaying it properly */
    switch (this.options.display_trigger) {
      case "delay":
        setTimeout(() => {
          display();
        }, this.options.display_trigger_value * 1000);

        break;

      case "exit_intent":
        let exit_intent_triggered = false;

        document.addEventListener("mouseout", (event) => {
          /* Get the current viewport width */
          let viewport_width = Math.max(
            document.documentElement.clientWidth,
            window.innerWidth || 0
          );

          // If the current mouse X position is within 50px of the right edge
          // of the viewport, return.
          if (event.clientX >= viewport_width - 50) return;

          // If the current mouse Y position is not within 50px of the top
          // edge of the viewport, return.
          if (event.clientY >= 50) return;

          // Reliable, works on mouse exiting window and
          // user switching active program
          let from = event.relatedTarget || event.toElement;
          if (!from && !exit_intent_triggered) {
            /* Exit intent happened */
            display();

            exit_intent_triggered = true;
          }
        });

        break;

      case "scroll":
        let scroll_triggered = false;

        document.addEventListener("scroll", (event) => {
          if (
            !scroll_triggered &&
            get_scroll_percentage() > this.options.display_trigger_value
          ) {
            display();

            scroll_triggered = true;
          }
        });

        break;
    }
  }

  is_page_triggered(triggers) {
    let triggered = false;

    /* If there is a Not type of condition, make sure to start with the triggered state of true */
    for (let trigger of triggers) {
      if (trigger.type.startsWith("not_")) {
        triggered = true;
        break;
      }
    }

    triggers.forEach((trigger) => {
      switch (trigger.type) {
        case "exact":
          if (trigger.value == window.location.href) {
            triggered = true;
          }

          break;

        case "not_exact":
          if (trigger.value == window.location.href) {
            triggered = false;
          }

          break;

        case "contains":
          if (window.location.href.includes(trigger.value)) {
            triggered = true;
          }

          break;

        case "not_contains":
          if (window.location.href.includes(trigger.value)) {
            triggered = false;
          }

          break;

        case "starts_with":
          if (window.location.href.startsWith(trigger.value)) {
            triggered = true;
          }

          break;

        case "not_starts_with":
          if (window.location.href.startsWith(trigger.value)) {
            triggered = false;
          }

          break;

        case "ends_with":
          if (window.location.href.endsWith(trigger.value)) {
            triggered = true;
          }

          break;

        case "not_ends_with":
          if (window.location.href.endsWith(trigger.value)) {
            triggered = false;
          }

          break;

        case "page_contains":
          if (document.body.innerText.includes(trigger.value)) {
            triggered = true;
          }

          break;
      }
    });

    return triggered;
  }

  /* Function to remove the notification with animation */
  static remove_notification(element) {
    try {
      /* Get animation data */
      let on_animation = element.getAttribute("data-on-animation");
      let off_animation = element.getAttribute("data-off-animation");

      /* Hide the element with an animation */
      element.className = element.className.replace(
        ` on-${on_animation}`,
        ` off-${off_animation}`
      );

      /* Remove the element from the DOM */
      window.setTimeout(() => {
        element.parentNode.removeChild(element);

        /* Recalculate position of other notifications */
        InPushManager.reposition();
      }, 400);
    } catch (event) {
      // ^_^
    }
  }

  /* Positioning function on the screen of all the notifications */
  static reposition() {
    let toasts = document.querySelectorAll(
      `div[class*="inpush"][class*="on-"]`
    );

    /* Get the height for later positioning usage in the middle of the screen */
    let height = window.innerHeight > 0 ? window.innerHeight : screen.height;
    let height_middle = Math.floor(height / 2);

    /* Default spacings that are going to be iterated if multiple toasts are on the same position */
    let toasts_offset = {
      top_left: {
        left: 20,
        top: 20,
      },

      top_center: {
        top: 20,
      },

      top_right: {
        right: 20,
        top: 20,
      },

      middle_left: {
        left: 20,
        top: height_middle,
      },

      middle_center: {
        top: height_middle,
      },

      middle_right: {
        right: 20,
        top: height_middle,
      },

      bottom_left: {
        left: 20,
        bottom: 20,
      },

      bottom_center: {
        bottom: 20,
      },

      bottom_right: {
        right: 20,
        bottom: 20,
      },
    };

    // Modifying the position of each toast element
    for (let i = toasts.length - 1; i >= 0; i--) {
      /* Spacing between stacked toasts */
      let toast_offset = 20;

      /* Get current position */
      let toast_position = toasts[i].getAttribute("data-position");

      /* Get height */
      let toast_height = toasts[i].offsetHeight;

      switch (toast_position) {
        /* When the notifications do not need to be fixed */
        default:
          continue;

          break;

        case "top_left":
          toasts[i].style["top"] = `${toasts_offset[toast_position].top}px`;
          toasts_offset[toast_position].top += toast_height + toast_offset;

          break;

        case "top_center":
          toasts[i].style["top"] = `${toasts_offset[toast_position].top}px`;
          toasts_offset[toast_position].top += toast_height + toast_offset;

          break;

        case "top_right":
          toasts[i].style["top"] = `${toasts_offset[toast_position].top}px`;
          toasts_offset[toast_position].top += toast_height + toast_offset;

          break;

        case "middle_left":
          toasts[i].style["top"] = `${
            toasts_offset[toast_position].top - toast_height / 2
          }px`;
          toasts_offset[toast_position].top += toast_height + toast_offset;

          break;

        case "middle_center":
          toasts[i].style["top"] = `${
            toasts_offset[toast_position].top - toast_height / 2
          }px`;
          toasts_offset[toast_position].top += toast_height + toast_offset;

          break;

        case "middle_right":
          toasts[i].style["top"] = `${
            toasts_offset[toast_position].top - toast_height / 2
          }px`;
          toasts_offset[toast_position].top += toast_height + toast_offset;

          break;

        case "bottom_left":
          toasts[i].style[
            "bottom"
          ] = `${toasts_offset[toast_position].bottom}px`;
          toasts_offset[toast_position].bottom += toast_height + toast_offset;

          break;

        case "bottom_center":
          toasts[i].style[
            "bottom"
          ] = `${toasts_offset[toast_position].bottom}px`;
          toasts_offset[toast_position].bottom += toast_height + toast_offset;

          break;

        case "bottom_right":
          toasts[i].style[
            "bottom"
          ] = `${toasts_offset[toast_position].bottom}px`;
          toasts_offset[toast_position].bottom += toast_height + toast_offset;

          break;
      }
    }
  }
}
