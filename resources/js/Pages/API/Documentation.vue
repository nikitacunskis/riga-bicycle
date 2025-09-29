<script setup>
import { ref, computed } from 'vue'
import FrontLayout from '@/Layouts/FrontLayout.vue'
import BodySection from '@/Components/BodySection.vue'

const pageTitle = 'Datu Eksports — API'

/** TOC */
const sections = [
    { id: 'overview', label: 'data' },
]

/** Language toggle */
const langs = ['curl', 'javascript', 'php']
const lang = ref('curl')
const pick = (l) => (lang.value = l)

/** Copy helper */
const copied = ref('')
async function copy(text, key = '') {
    try {
        await navigator.clipboard.writeText(text)
        copied.value = key || text.slice(0, 24) + '…'
        setTimeout(() => (copied.value = ''), 1600)
    } catch { alert('Copy failed') }
}

/** URLs */
const baseUrl = computed(() => `${location.origin}`)
const endpoint = computed(() => `${baseUrl.value}/data`)

/** Demo key ONLY for examples */
const demoKey = 'sk_live_xxxxREDACTEDxxxx'

/** Code samples */
const samples = computed(() => ({
    basicCurl: `curl -s '${endpoint.value}?key=${demoKey}'`,
    basicJs: `await fetch('${endpoint.value}?key=${demoKey}')
  .then(r => r.json())`,
    basicPhp: `use Illuminate\\Support\\Facades\\Http;
$response = Http::get('${endpoint.value}', ['key' => '${demoKey}']);
$data = $response->json();`,

    groupEventCurl: `curl -s '${endpoint.value}?key=${demoKey}&group=event'`,
    groupPlaceCurl: `curl -s '${endpoint.value}?key=${demoKey}&group=place'`,

    csvCurl:  `curl -L '${endpoint.value}?key=${demoKey}&format=csv'  -o reports.csv`,
    xlsxCurl: `curl -L '${endpoint.value}?key=${demoKey}&format=xlsx' -o reports.xlsx`,
}))

/** Single endpoint (kept in a table for future growth) */
const endpoints = [
    { method: 'GET', path: '/data', summary: 'Atgriež atskaites JSON vai CSV/XLSX', auth: 'required (?key=...)' },
]

const q = ref('')
const filtered = computed(() => {
    const v = q.value.trim().toLowerCase()
    if (!v) return endpoints
    return endpoints.filter(e => [e.method, e.path, e.summary].join(' ').toLowerCase().includes(v))
})

function scrollToId(id) {
    const el = document.getElementById(id)
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
}
</script>

<template>
    <FrontLayout :title="pageTitle">

        <!-- Subtle grid background to match the site -->
        <div class="">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Overview -->
                    <section id="overview" class="scroll-mt-28">
                        <h3 class="text-lg font-semibold mb-2">Pārskats</h3>
                        <p class="text-sm">
                            Vienots endpoint, kas atgriež <span class="font-medium">atskaites</span> ar saistīto
                            <code class="font-mono">event</code> un <code class="font-mono">place</code>.
                            Pēc noklusējuma — JSON; ar <code class="font-mono">format</code> var lejupielādēt <code class="font-mono">csv</code> vai <code class="font-mono">xlsx</code>.
                            JSON var grupēt pēc <code class="font-mono">event</code> vai <code class="font-mono">place</code>.
                        </p>
                    </section>

                    <!-- Endpoint -->
                    <section id="endpoint" class="scroll-mt-28">
                        <div class="flex items-end justify-between gap-3 mb-2">
                            <h3 class="text-lg font-semibold  ">Endpoint</h3>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-emerald-200/80 dark:border-emerald-900/40 bg-white/80 dark:bg-emerald-900/30 shadow-sm">
                            <table class="min-w-full text-sm">
                                <thead class="bg-emerald-50/80 dark:bg-emerald-900/40  dark:text-emerald-100">
                                <tr>
                                    <th class="text-left p-2">Method</th>
                                    <th class="text-left p-2">Path</th>
                                    <th class="text-left p-2">Summary</th>
                                    <th class="text-left p-2">Auth</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="e in filtered" :key="e.path+e.method" class="border-t border-emerald-100 dark:border-emerald-900/30">
                                    <td class="p-2 font-mono">
                    <span class="px-2 py-0.5 rounded text-xs bg-emerald-100  dark:bg-emerald-900/50 dark:text-emerald-100">
                      {{ e.method }}
                    </span>
                                    </td>
                                    <td class="p-2 font-mono ">{{ e.path }}</td>
                                    <td class="p-2 ">{{ e.summary }}</td>
                                    <td class="p-2 text-xs ">{{ e.auth }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Params -->
                    <section id="params" class="scroll-mt-28">
                        <h3 class="text-lg font-semibold   mb-2">Parametri</h3>
                        <ul class="list-disc pl-5 text-sm  space-y-1">
                            <li><code class="font-mono">key</code> <span class="">(required)</span> – API atslēga.</li>
                            <li><code class="font-mono">group</code> <span class="">(optional)</span> – <code>event</code> | <code>place</code>. Citi varianti → <code>422</code>.</li>
                            <li><code class="font-mono">format</code> <span class="">(optional)</span> – <code>csv</code> | <code>xlsx</code>. Ja norādi, atgriež failu; citādi — JSON.</li>
                        </ul>

                        <!-- Quick requests block -->
                        <div class="mt-3 rounded-xl border border-emerald-200/80 dark:border-emerald-900/40 bg-emerald-50/60 dark:bg-emerald-900/20 p-3 space-y-2 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="text-xs uppercase tracking-wide ">Ātrie pieprasījumi</div>
                                <div class="flex items-center gap-1 text-xs">
                                    <button v-for="l in langs" :key="l" @click="pick(l)"
                                            :class="['px-2 py-1 border rounded', lang===l ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white/70 dark:bg-emerald-900/30 border-emerald-300/70 hover:bg-emerald-100 dark:hover:bg-emerald-900/40']">
                                        {{ l }}
                                    </button>
                                </div>
                            </div>

                            <pre class="doc-code"><code v-if="lang==='curl'">{{ samples.basicCurl }}</code><code v-else-if="lang==='javascript'">{{ samples.basicJs }}</code><code v-else>{{ samples.basicPhp }}</code></pre>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <pre class="doc-code"><code>{{ samples.groupEventCurl }}</code></pre>
                                <pre class="doc-code"><code>{{ samples.groupPlaceCurl }}</code></pre>
                            </div>
                            <pre class="doc-code"><code>{{ samples.csvCurl }}</code></pre>
                            <pre class="doc-code"><code>{{ samples.xlsxCurl }}</code></pre>

                            <div class="text-right">
                                <button class="text-xs underline text-emerald-700 hover:"
                                        @click="copy(lang==='curl'?samples.basicCurl:lang==='javascript'?samples.basicJs:samples.basicPhp, 'request-example')">
                                    Kopēt
                                </button>
                            </div>
                        </div>
                    </section>

                    <!-- Responses -->
                    <section id="responses" class="scroll-mt-28">
                        <h3 class="text-lg font-semibold   mb-2">Atbildes</h3>

                        <div class="doc-card">
                            <div class="doc-card-title">JSON (bez grupēšanas)</div>
                            <pre class="doc-code">GET {{ endpoint }}?key=YOUR_KEY
{
"reports": [
{ "id": 1, "event": { /* … */ }, "place": { /* … */ } },
{ "id": 2, "event": { /* … */ }, "place": { /* … */ } }
],
"apiData": { /* Api model */ },
"meta": { "group": null, "count": 123, "format": "json" }
}
// place_id un event_id paslēpti katrā report.</pre>
                        </div>

                        <div class="doc-card">
                            <div class="doc-card-title">JSON grupēts pēc <code class="font-mono">event</code></div>
                            <pre class="doc-code">GET {{ endpoint }}?key=YOUR_KEY&group=event
{
"groupedReports": {
"&lt;eventId&gt;": {
  "event": { /* event */ },
  "reports": [ /* … */ ],
  "by_place": { "&lt;placeId&gt;": [ /* … */ ] }
}
},
"apiData": { /* … */ },
"meta": { "group": "event", "count": 123, "format": "json" }
}</pre>
                        </div>

                        <div class="doc-card">
                            <div class="doc-card-title">JSON grupēts pēc <code class="font-mono">place</code></div>
                            <pre class="doc-code">GET {{ endpoint }}?key=YOUR_KEY&group=place
{
"groupedReports": {
"&lt;placeId&gt;": {
  "place": { /* place */ },
  "reports": [ /* … */ ],
  "by_event": { "&lt;eventId&gt;": [ /* … */ ] }
}
},
"apiData": { /* … */ },
"meta": { "group": "place", "count": 123, "format": "json" }
}</pre>
                        </div>

                        <div class="doc-card">
                            <div class="doc-card-title">Failu lejupielādes</div>
                            <pre class="doc-code"># CSV
curl -L '{{ endpoint }}?key=YOUR_KEY&format=csv' -o reports.csv

# Excel (XLSX)
curl -L '{{ endpoint }}?key=YOUR_KEY&format=xlsx' -o reports.xlsx

# Piezīme: CSV/XLSX tiek “flattened” (grupas sheetos netiek veidotas).</pre>
                        </div>
                    </section>

                    <!-- Errors -->
                    <section id="errors" class="scroll-mt-28">
                        <h3 class="text-lg font-semibold   mb-2">Kļūdas</h3>
                        <pre class="doc-code">// 404 – invalid API key
{ "error": "API key not valid" }</pre>
                        <pre class="doc-code">// 422 – bad group value
{ "error": "Invalid group value. Use \"event\" or \"place\"." }</pre>
                        <pre class="doc-code">// 422 – bad format value
{ "error": "Invalid format. Use \"xlsx\" or \"csv\"." }</pre>
                    </section>

                    <!-- Examples -->
                    <section id="examples" class="scroll-mt-28">
                        <h3 class="text-lg font-semibold   mb-2">Piemēri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="doc-card">
                                <div class="doc-card-title">JavaScript (fetch)</div>
                                <pre class="doc-code">const res = await fetch('{{ endpoint }}?key=YOUR_KEY')
if (!res.ok) throw new Error('Request failed: ' + res.status)
const data = await res.json()</pre>
                            </div>

                            <div class="doc-card">
                                <div class="doc-card-title">PHP (Laravel HTTP client)</div>
                                <pre class="doc-code">use Illuminate\\Support\\Facades\\Http;
$resp = Http::get('{{ endpoint }}', [ 'key' => 'YOUR_KEY', 'group' => 'event' ]);
if ($resp->failed()) { /* handle */ }
return $resp->json();</pre>
                            </div>
                        </div>
                    </section>

                    <!-- Changelog -->
                    <section id="changelog" class="scroll-mt-28">
                        <h3 class="text-lg font-semibold   mb-2">Izmaiņu žurnāls</h3>
                        <ul class="text-sm  list-disc pl-5">
                            <li><span class="font-medium">2025-09-29</span> — Saskaņots ar <code class="font-mono">GET /data</code> (key, group, format).</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </FrontLayout>
</template>

<style scoped>
    .doc-card {
        @apply mt-5 border border-emerald-200/80 dark:border-emerald-900/40 rounded-xl bg-white/80 dark:bg-emerald-900/30 p-3 shadow-sm;
    }
    .doc-card-title {
        @apply mt-5 text-sm font-medium mb-1 ;
    }
    .doc-code {
        @apply mt-5 text-xs overflow-auto p-3 rounded-lg bg-emerald-900 text-emerald-50;
        /* keep code readable on light theme too */
        background: linear-gradient(180deg, #052e25, #062f27);
    }
</style>
