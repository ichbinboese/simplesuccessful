<template>
  <section class="card space-y-8">
    <header class="flex flex-col gap-3">
      <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-600 dark:text-slate-300 w-fit">
        <span class="size-2 rounded-full bg-emerald-400"></span>
        Upload-Center
      </div>
      <div>
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Neues Video hochladen</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Lade dein finales Video als MP4 oder MOV hoch. Die Datei landet sicher in der Content-Bibliothek.
        </p>
      </div>
    </header>

    <form class="space-y-6" @submit.prevent="submit">
      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Titel</label>
          <input
            v-model="title"
            type="text"
            class="input"
            placeholder="z. B. Onboarding-Video KW45"
            :disabled="uploading"
          />
        </div>

        <div class="space-y-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Video-Datei</label>
          <div
            class="rounded-2xl border-2 border-dashed p-4 transition bg-slate-50/60 dark:bg-slate-900/30"
            :class="file ? 'border-emerald-300 dark:border-emerald-400' : 'border-slate-200 dark:border-slate-700'"
          >
            <input
              ref="fileInput"
              type="file"
              class="sr-only"
              accept="video/mp4,video/mpeg,video/quicktime,video/webm"
              @change="onFileChange"
            />
            <div class="flex flex-col gap-3">
              <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-white/60 dark:bg-white/10 p-3 shadow-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0-3 3m3-3 3 3m-8.25 6.75h10.5a1.5 1.5 0 001.5-1.5V7.125a1.5 1.5 0 00-.44-1.06l-2.625-2.625A1.5 1.5 0 0014.94 3H7.5a1.5 1.5 0 00-1.5 1.5V18a1.5 1.5 0 001.5 1.5z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ fileName || 'Noch keine Datei ausgewählt' }}
                  </p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ fileSize || 'MP4, MOV oder WebM • bis 2 GB' }}
                  </p>
                </div>
                <button type="button" class="btn btn-secondary whitespace-nowrap" @click="triggerFileSelect" :disabled="uploading">
                  Datei wählen
                </button>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400">
                Tipp: Benenne Dateien eindeutig. Während des Uploads kannst du im Browser bleiben.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div v-if="uploading" class="space-y-2">
        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
          <span>Upload läuft…</span>
          <span>{{ progress }}%</span>
        </div>
        <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
          <div
            class="h-full rounded-full bg-gradient-to-r from-sky-500 via-indigo-500 to-violet-500 transition-all duration-200"
            :style="{ width: `${progress}%` }"
          ></div>
        </div>
      </div>

      <div v-if="message" :class="messageClass">
        <svg v-if="messageType==='success'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ message }}</span>
      </div>

      <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn btn-primary" :disabled="uploading">
          {{ uploading ? 'Wird übertragen…' : 'Upload starten' }}
        </button>
        <button type="button" class="btn btn-ghost text-slate-500 hover:text-slate-700" @click="reset()" :disabled="uploading">
          Zurücksetzen
        </button>
      </div>
    </form>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      title: '',
      file: null,
      fileName: '',
      fileSize: '',
      uploading: false,
      progress: 0,
      message: '',
      messageType: null,
    };
  },
  computed: {
    messageClass() {
      const base = 'flex items-center gap-2 rounded-xl px-3 py-2 text-sm';
      if (this.messageType === 'success') {
        return `${base} bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300`;
      }
      return `${base} bg-rose-50 text-rose-700 border border-rose-100 dark:bg-rose-500/10 dark:text-rose-300`;
    },
  },
  methods: {
    triggerFileSelect() {
      this.$refs.fileInput?.click();
    },
    onFileChange(event) {
      const file = event.target.files?.[0];
      this.file = file || null;
      if (file) {
        this.fileName = file.name;
        this.fileSize = this.formatBytes(file.size);
      } else {
        this.fileName = '';
        this.fileSize = '';
      }
    },
    formatBytes(bytes) {
      if (!bytes) return '';
      const units = ['B', 'KB', 'MB', 'GB'];
      const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
      const value = bytes / Math.pow(1024, index);
      return `${value.toFixed(value >= 10 ? 0 : 1)} ${units[index]}`;
    },
    setMessage(text, type = 'error') {
      this.message = text;
      this.messageType = type;
    },
    reset(clearMessage = true) {
      this.title = '';
      this.file = null;
      this.fileName = '';
      this.fileSize = '';
      this.uploading = false;
      this.progress = 0;
      if (clearMessage) {
        this.message = '';
        this.messageType = null;
      }
      if (this.$refs.fileInput) {
        this.$refs.fileInput.value = '';
      }
    },
    async submit() {
      if (!this.title.trim()) {
        return this.setMessage('Bitte gib dem Video einen Titel.', 'error');
      }
      if (!this.file) {
        return this.setMessage('Bitte wähle eine Videodatei aus.', 'error');
      }

      const fd = new FormData();
      fd.append('file', this.file);
      fd.append('title', this.title.trim());

      this.uploading = true;
      this.progress = 0;
      this.message = '';
      this.messageType = null;

      try {
        const response = await axios.post('/api/admin/video/upload', fd, {
          headers: { 'Content-Type': 'multipart/form-data' },
          onUploadProgress: (event) => {
            if (event.total) {
              this.progress = Math.round((event.loaded / event.total) * 100);
            }
          },
        });

        if (response.data?.ok) {
          this.setMessage('Upload erfolgreich abgeschlossen.', 'success');
          this.$emit('uploaded');
          this.reset(false);
        } else {
          this.setMessage('Upload fehlgeschlagen. Bitte versuche es erneut.');
        }
      } catch (error) {
        this.setMessage(error.response?.data?.message ?? 'Upload fehlgeschlagen. Bitte versuche es erneut.');
      } finally {
        this.uploading = false;
      }
    },
  },
};
</script>
