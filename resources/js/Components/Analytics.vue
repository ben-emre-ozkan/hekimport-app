<template>
  <div v-if="analyticsId">
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ analyticsId }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ analyticsId }}');
    </script>
  </div>
</template>

<script setup>
defineProps({
  analyticsId: {
    type: String,
    required: true
  }
});
</script> 