window.coreTheme.blocks("grid",((e,t)=>{const s=e.querySelector(".js-load-btn"),o=e.querySelector(".posts");s&&s.addEventListener("click",(()=>{let{offset:e,totalposts:r,queryargs:a}=s.dataset;if(e<r){o.classList.add("loading");let r=JSON.parse(a.replace(/&quot;/g,'"'));(async e=>{if(e.length<0)return null;try{const t=await fetch("/wp-json/core/v1/posts/",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(e)});if(!t.ok)throw new Error(`HTTP error! Status: ${t.status}`);return s.dataset.offset=parseInt(e.offset)+parseInt(e.posts_per_page),await t.json()}catch(e){console.error("Fetch error:",e)}})({component:"post-card",offset:e,fields:t,...r}).then((e=>(e=>{e.results.length<1||e.html.length>0&&e.html.forEach((e=>{o.innerHTML+=e}))})(e))).finally((()=>{o.classList.remove("loading"),window.packages.animations()}))}else s.remove()}))}));
//# sourceMappingURL=grid.js.map