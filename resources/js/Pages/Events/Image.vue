<script setup>
import { ref } from 'vue';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    post_text: String,
    image_url: String,
    event_id: String,
});

const posting = ref(false);
const result = ref(null);

async function postToX(withImage = true) {
    posting.value = true;
    result.value = null;

    try {
        const url = withImage
            ? `/dashboard/events/${props.event_id}/share-post`
            : `/dashboard/events/${props.event_id}/share-post?no-image=1`;

        const res = await axios.post(url);
        result.value = res.data;
    } catch (e) {
        result.value = { ok: false, error: e.response?.data || e.message };
    } finally {
        posting.value = false;
    }
}
</script>

<template>
    <AdminLayout title="Preview Report">

        <p style="white-space:pre-wrap;font-size:16px;margin-bottom:16px;">{{ post_text }}</p>
        <img :src="image_url" style="max-width:100%;border-radius:6px;" />

        <div style="margin-top: 20px; display:flex; gap:12px;">
            <!-- Publish WITH IMAGE -->
            <button
                @click="postToX(true)"
                :disabled="posting"
                style="background:#1d9bf0;color:white;border:none;padding:10px 16px;border-radius:6px;font-weight:600;cursor:pointer;"
            >
                {{ posting ? 'Posting…' : 'Post to X (with image)' }}
            </button>

            <!-- Publish ONLY TEXT -->
            <button
                @click="postToX(false)"
                :disabled="posting"
                style="background:#586e75;color:white;border:none;padding:10px 16px;border-radius:6px;font-weight:600;cursor:pointer;"
            >
                {{ posting ? 'Posting…' : 'Post text only' }}
            </button>
        </div>

        <div v-if="result" style="margin-top: 20px;">
            <pre style="background:black;color:white;padding:12px;border-radius:6px;font-size:13px;">
{{ result }}
            </pre>
        </div>

    </AdminLayout>
</template>

<style>
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
