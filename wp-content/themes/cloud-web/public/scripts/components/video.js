window.coreTheme.components("video",(e=>{const s=e.querySelector(".video-play-button"),a=e.querySelector(".play-button-wrapper"),t=e.querySelector(".js-cover-image");t&&s.addEventListener("click",(function(){const i=e.querySelector("video, iframe");if(i){if("VIDEO"===i.tagName)i.play();else if("IFRAME"===i.tagName){const e=new URL(i.dataset.src);e.searchParams.set("autoplay",1),i.src=e}t.classList.add("is-hidden"),s.classList.add("is-hidden"),a.classList.add("is-hidden")}}))}));
//# sourceMappingURL=video.js.map