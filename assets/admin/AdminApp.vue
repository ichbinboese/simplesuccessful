<template>
  <div class="admin">
    <h1>Admin</h1>
    <video-upload @uploaded="refresh" />
    <video-list :videos="videos" @refresh="refresh" />
  </div>
</template>

<script>
import axios from 'axios';
import VideoUpload from './components/VideoUpload.vue';
import VideoList from './components/VideoList.vue';

export default {
  components: { VideoUpload, VideoList },
  data(){ return { videos: [] }; },
  mounted(){ this.refresh(); },
  methods: {
    refresh(){ axios.get('/api/admin/video/list').then(r=>{ this.videos = r.data; }); }
  }
}
</script>
