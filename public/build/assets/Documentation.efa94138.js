import{F as $}from"./FrontLayout.9b9bcf85.js";import{_ as w}from"./_plugin-vue_export-helper.cdc0426e.js";import{r as m,c as p,a as E,w as S,o as n,b as e,d as t,e as u,f as g,F as x,t as o,n as P}from"./app.ff9256fe.js";const j={class:""},I={class:"grid grid-cols-1 lg:grid-cols-3 gap-6"},O={class:"lg:col-span-3 space-y-6"},Y={id:"endpoint",class:"scroll-mt-28"},J={class:"overflow-x-auto rounded-xl border border-emerald-200/80 dark:border-emerald-900/40 bg-white/80 dark:bg-emerald-900/30 shadow-sm"},L={class:"min-w-full text-sm"},T={class:"p-2 font-mono"},R={class:"px-2 py-0.5 rounded text-xs bg-emerald-100 dark:bg-emerald-900/50 dark:text-emerald-100"},D={class:"p-2 font-mono"},K={class:"p-2"},U={class:"p-2 text-xs"},A={id:"params",class:"scroll-mt-28"},N={class:"mt-3 rounded-xl border border-emerald-200/80 dark:border-emerald-900/40 bg-emerald-50/60 dark:bg-emerald-900/20 p-3 space-y-2 shadow-sm"},F={class:"flex items-center justify-between"},H={class:"flex items-center gap-1 text-xs"},V=["onClick"],X={class:"doc-code"},q={key:0},G={key:1},z={key:2},B={class:"grid grid-cols-1 md:grid-cols-2 gap-2"},M={class:"doc-code"},Q={class:"doc-code"},W={class:"doc-code"},Z={class:"doc-code"},ee={class:"text-right"},se={id:"responses",class:"scroll-mt-28"},te={class:"doc-card"},oe={class:"doc-code"},ae={class:"doc-card"},le={class:"doc-code"},re={class:"doc-card"},de={class:"doc-code"},ne={class:"doc-card"},ce={class:"doc-code"},ie={id:"examples",class:"scroll-mt-28"},ue={class:"grid grid-cols-1 md:grid-cols-2 gap-4"},pe={class:"doc-card"},me={class:"doc-code"},ve={class:"doc-card"},_e={class:"doc-code"},ge="Datu Eksports \u2014 API",c="sk_live_xxxxREDACTEDxxxx",xe={__name:"Documentation",setup(fe){const f=["curl","javascript","php"],i=m("curl"),b=d=>i.value=d,v=m("");async function h(d,s=""){try{await navigator.clipboard.writeText(d),v.value=s||d.slice(0,24)+"\u2026",setTimeout(()=>v.value="",1600)}catch{alert("Copy failed")}}const k=p(()=>`${location.origin}`),a=p(()=>`${k.value}/data`),r=p(()=>({basicCurl:`curl -s '${a.value}?key=${c}'`,basicJs:`await fetch('${a.value}?key=${c}')
  .then(r => r.json())`,basicPhp:`use Illuminate\\Support\\Facades\\Http;
$response = Http::get('${a.value}', ['key' => '${c}']);
$data = $response->json();`,groupEventCurl:`curl -s '${a.value}?key=${c}&group=event'`,groupPlaceCurl:`curl -s '${a.value}?key=${c}&group=place'`,csvCurl:`curl -L '${a.value}?key=${c}&format=csv'  -o reports.csv`,xlsxCurl:`curl -L '${a.value}?key=${c}&format=xlsx' -o reports.xlsx`})),_=[{method:"GET",path:"/data",summary:"Atgrie\u017E atskaites JSON vai CSV/XLSX",auth:"required (?key=...)"}],y=m(""),C=p(()=>{const d=y.value.trim().toLowerCase();return d?_.filter(s=>[s.method,s.path,s.summary].join(" ").toLowerCase().includes(d)):_});return(d,s)=>(n(),E($,{title:ge},{default:S(()=>[e("div",j,[e("div",I,[e("div",O,[s[14]||(s[14]=e("section",{id:"overview",class:"scroll-mt-28"},[e("h3",{class:"text-lg font-semibold mb-2"},"P\u0101rskats"),e("p",{class:"text-sm"},[t(" Vienots endpoint, kas atgrie\u017E "),e("span",{class:"font-medium"},"atskaites"),t(" ar saist\u012Bto "),e("code",{class:"font-mono"},"event"),t(" un "),e("code",{class:"font-mono"},"place"),t(". P\u0113c noklus\u0113juma \u2014 JSON; ar "),e("code",{class:"font-mono"},"format"),t(" var lejupiel\u0101d\u0113t "),e("code",{class:"font-mono"},"csv"),t(" vai "),e("code",{class:"font-mono"},"xlsx"),t(". JSON var grup\u0113t p\u0113c "),e("code",{class:"font-mono"},"event"),t(" vai "),e("code",{class:"font-mono"},"place"),t(". ")])],-1)),e("section",Y,[s[2]||(s[2]=e("div",{class:"flex items-end justify-between gap-3 mb-2"},[e("h3",{class:"text-lg font-semibold"},"Endpoint")],-1)),e("div",J,[e("table",L,[s[1]||(s[1]=e("thead",{class:"bg-emerald-50/80 dark:bg-emerald-900/40 dark:text-emerald-100"},[e("tr",null,[e("th",{class:"text-left p-2"},"Method"),e("th",{class:"text-left p-2"},"Path"),e("th",{class:"text-left p-2"},"Summary"),e("th",{class:"text-left p-2"},"Auth")])],-1)),e("tbody",null,[(n(!0),u(x,null,g(C.value,l=>(n(),u("tr",{key:l.path+l.method,class:"border-t border-emerald-100 dark:border-emerald-900/30"},[e("td",T,[e("span",R,o(l.method),1)]),e("td",D,o(l.path),1),e("td",K,o(l.summary),1),e("td",U,o(l.auth),1)]))),128))])])])]),e("section",A,[s[4]||(s[4]=e("h3",{class:"text-lg font-semibold mb-2"},"Parametri",-1)),s[5]||(s[5]=e("ul",{class:"list-disc pl-5 text-sm space-y-1"},[e("li",null,[e("code",{class:"font-mono"},"key"),t(),e("span",{class:""},"(required)"),t(" \u2013 API atsl\u0113ga.")]),e("li",null,[e("code",{class:"font-mono"},"group"),t(),e("span",{class:""},"(optional)"),t(" \u2013 "),e("code",null,"event"),t(" | "),e("code",null,"place"),t(". Citi varianti \u2192 "),e("code",null,"422"),t(".")]),e("li",null,[e("code",{class:"font-mono"},"format"),t(),e("span",{class:""},"(optional)"),t(" \u2013 "),e("code",null,"csv"),t(" | "),e("code",null,"xlsx"),t(". Ja nor\u0101di, atgrie\u017E failu; cit\u0101di \u2014 JSON.")])],-1)),e("div",N,[e("div",F,[s[3]||(s[3]=e("div",{class:"text-xs uppercase tracking-wide"},"\u0100trie piepras\u012Bjumi",-1)),e("div",H,[(n(),u(x,null,g(f,l=>e("button",{key:l,onClick:be=>b(l),class:P(["px-2 py-1 border rounded",i.value===l?"bg-emerald-600 text-white border-emerald-600":"bg-white/70 dark:bg-emerald-900/30 border-emerald-300/70 hover:bg-emerald-100 dark:hover:bg-emerald-900/40"])},o(l),11,V)),64))])]),e("pre",X,[i.value==="curl"?(n(),u("code",q,o(r.value.basicCurl),1)):i.value==="javascript"?(n(),u("code",G,o(r.value.basicJs),1)):(n(),u("code",z,o(r.value.basicPhp),1))]),e("div",B,[e("pre",M,[e("code",null,o(r.value.groupEventCurl),1)]),e("pre",Q,[e("code",null,o(r.value.groupPlaceCurl),1)])]),e("pre",W,[e("code",null,o(r.value.csvCurl),1)]),e("pre",Z,[e("code",null,o(r.value.xlsxCurl),1)]),e("div",ee,[e("button",{class:"text-xs underline text-emerald-700 hover:",onClick:s[0]||(s[0]=l=>h(i.value==="curl"?r.value.basicCurl:i.value==="javascript"?r.value.basicJs:r.value.basicPhp,"request-example"))}," Kop\u0113t ")])])]),e("section",se,[s[10]||(s[10]=e("h3",{class:"text-lg font-semibold mb-2"},"Atbildes",-1)),e("div",te,[s[6]||(s[6]=e("div",{class:"doc-card-title"},"JSON (bez grup\u0113\u0161anas)",-1)),e("pre",oe,"GET "+o(a.value)+`?key=YOUR_KEY
{
"reports": [
{ "id": 1, "event": { /* \u2026 */ }, "place": { /* \u2026 */ } },
{ "id": 2, "event": { /* \u2026 */ }, "place": { /* \u2026 */ } }
],
"apiData": { /* Api model */ },
"meta": { "group": null, "count": 123, "format": "json" }
}
// place_id un event_id pasl\u0113pti katr\u0101 report.`,1)]),e("div",ae,[s[7]||(s[7]=e("div",{class:"doc-card-title"},[t("JSON grup\u0113ts p\u0113c "),e("code",{class:"font-mono"},"event")],-1)),e("pre",le,"GET "+o(a.value)+`?key=YOUR_KEY&group=event
{
"groupedReports": {
"<eventId>": {
  "event": { /* event */ },
  "reports": [ /* \u2026 */ ],
  "by_place": { "<placeId>": [ /* \u2026 */ ] }
}
},
"apiData": { /* \u2026 */ },
"meta": { "group": "event", "count": 123, "format": "json" }
}`,1)]),e("div",re,[s[8]||(s[8]=e("div",{class:"doc-card-title"},[t("JSON grup\u0113ts p\u0113c "),e("code",{class:"font-mono"},"place")],-1)),e("pre",de,"GET "+o(a.value)+`?key=YOUR_KEY&group=place
{
"groupedReports": {
"<placeId>": {
  "place": { /* place */ },
  "reports": [ /* \u2026 */ ],
  "by_event": { "<eventId>": [ /* \u2026 */ ] }
}
},
"apiData": { /* \u2026 */ },
"meta": { "group": "place", "count": 123, "format": "json" }
}`,1)]),e("div",ne,[s[9]||(s[9]=e("div",{class:"doc-card-title"},"Failu lejupiel\u0101des",-1)),e("pre",ce,`# CSV
curl -L '`+o(a.value)+`?key=YOUR_KEY&format=csv' -o reports.csv

# Excel (XLSX)
curl -L '`+o(a.value)+`?key=YOUR_KEY&format=xlsx' -o reports.xlsx

# Piez\u012Bme: CSV/XLSX tiek \u201Cflattened\u201D (grupas sheetos netiek veidotas).`,1)])]),s[15]||(s[15]=e("section",{id:"errors",class:"scroll-mt-28"},[e("h3",{class:"text-lg font-semibold mb-2"},"K\u013C\u016Bdas"),e("pre",{class:"doc-code"},`// 404 \u2013 invalid API key
{ "error": "API key not valid" }`),e("pre",{class:"doc-code"},`// 422 \u2013 bad group value
{ "error": "Invalid group value. Use \\"event\\" or \\"place\\"." }`),e("pre",{class:"doc-code"},`// 422 \u2013 bad format value
{ "error": "Invalid format. Use \\"xlsx\\" or \\"csv\\"." }`)],-1)),e("section",ie,[s[13]||(s[13]=e("h3",{class:"text-lg font-semibold mb-2"},"Piem\u0113ri",-1)),e("div",ue,[e("div",pe,[s[11]||(s[11]=e("div",{class:"doc-card-title"},"JavaScript (fetch)",-1)),e("pre",me,"const res = await fetch('"+o(a.value)+`?key=YOUR_KEY')
if (!res.ok) throw new Error('Request failed: ' + res.status)
const data = await res.json()`,1)]),e("div",ve,[s[12]||(s[12]=e("div",{class:"doc-card-title"},"PHP (Laravel HTTP client)",-1)),e("pre",_e,`use Illuminate\\\\Support\\\\Facades\\\\Http;
$resp = Http::get('`+o(a.value)+`', [ 'key' => 'YOUR_KEY', 'group' => 'event' ]);
if ($resp->failed()) { /* handle */ }
return $resp->json();`,1)])])]),s[16]||(s[16]=e("section",{id:"changelog",class:"scroll-mt-28"},[e("h3",{class:"text-lg font-semibold mb-2"},"Izmai\u0146u \u017Eurn\u0101ls"),e("ul",{class:"text-sm list-disc pl-5"},[e("li",null,[e("span",{class:"font-medium"},"2025-09-29"),t(" \u2014 Saska\u0146ots ar "),e("code",{class:"font-mono"},"GET /data"),t(" (key, group, format).")])])],-1))])])])]),_:1}))}},Ce=w(xe,[["__scopeId","data-v-f6b9eed5"]]);export{Ce as default};
