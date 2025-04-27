<template>
  <div class="booking-calendar-container">
    <h1>Lịch Sân</h1>
    <div class="calendar-wrapper">
      <!-- Calendar content will be added here -->
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'BookingCalendarView',
  components: {
  },
  setup() {
    return {
      // setup logic will be added here
    }
  }
})
</script>

<style scoped>
.booking-calendar-container {
  padding: 20px;
}

.calendar-wrapper {
  margin-top: 20px;
}
</style> 