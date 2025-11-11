<template>
  <div>
    <h2>Video hochladen</h2>
    <form @submit.prevent="submit">
      <input type="text" v-model="title" placeholder="Titel" />
      <input type="file" ref="file" />
      <button type="submit">Upload</button>
    </form>
    <div v-if="message">{{ message }}</div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data(){ return { title: '', message: '' }; },
  methods: {
    async submit(){
      const f = this.$refs.file.files[0];
      if(!f) return this.message='Bitte Datei wählen';
      const fd = new FormData();
      fd.append('file', f);
      fd.append('title', this.title);
      const res = await axios.post('/api/admin/video/upload', fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      this.message = res.data.ok ? 'Erfolgreich' : 'Fehler';
      this.$emit('uploaded');
    }
  }
}
</script>
