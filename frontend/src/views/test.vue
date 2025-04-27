<script setup lang="ts">
import { onMounted, ref } from "vue";
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import resourceTimeGridPlugin from "@fullcalendar/resource-timegrid";
import interactionPlugin from "@fullcalendar/interaction";

const calendar = ref<HTMLElement | null>(null);

// Hàm tải sự kiện từ localStorage
const loadEventsFromLocalStorage = () => {
  const storedEvents = localStorage.getItem("events");
  return storedEvents ? JSON.parse(storedEvents).map((event: any) => {
    event.start = new Date(event.start); // Chuyển start từ chuỗi thành đối tượng Date
    return event;
  }) : [];
};

// Hàm lưu sự kiện vào localStorage
const saveEventsToLocalStorage = (events: any[]) => {
  localStorage.setItem("events", JSON.stringify(events.map(event => ({
    title: event.title,
    start: event.start.toISOString(),
    resourceId: event.extendedProps.resourceId
  }))));
};

onMounted(() => {
  if (!calendar.value) return;

  const resources = [
    { id: "1", title: "Sân 1" },
    { id: "2", title: "Sân 2" },
    { id: "3", title: "Sân 3" },
    { id: "4", title: "Sân 4" },
    { id: "5", title: "Sân 5" },
    { id: "6", title: "Sân 6" },
    { id: "7", title: "Sân 7" },
    { id: "8", title: "Sân 8" },
    { id: "9", title: "Sân 9" },
    { id: "10", title: "Sân 10" },
    { id: "11", title: "Sân 11" }
  ];

  let calendarInstance = new Calendar(calendar.value, {
    plugins: [dayGridPlugin, timeGridPlugin, resourceTimeGridPlugin, interactionPlugin],
    initialView: "resourceTimeGridDay", // Đặt "Ngày" làm chế độ mặc định
    resources: resources,
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,resourceTimeGridWeek,resourceTimeGridDay",
    },
    selectable: true,
    editable: true,
    events: [
      ...loadEventsFromLocalStorage(), // Load events từ localStorage
      {
        title: "Sự kiện cố định Sân 1",
        start: "2025-03-23T08:00:00",
        resourceId: "1",
        editable: false, // Không cho chỉnh sửa
        backgroundColor: "lightblue", // Màu nền cho sự kiện cố định
        borderColor: "blue",
        textColor: "darkblue",
      },
      {
        title: "Sự kiện cố định Sân 2",
        start: "2025-03-23T10:00:00",
        resourceId: "2",
        editable: false, // Không cho chỉnh sửa
        backgroundColor: "lightgreen", // Màu nền cho sự kiện cố định
        borderColor: "green",
        textColor: "darkgreen",
      },
      {
        title: "Sự kiện cố định Sân 3",
        start: "2025-03-23T12:00:00",
        resourceId: "3",
        editable: false, // Không cho chỉnh sửa
        backgroundColor: "lightcoral", // Màu nền cho sự kiện cố định
        borderColor: "red",
        textColor: "darkred",
      }
    ],
    dateClick: function (info) {
      let title = prompt("Nhập tên sự kiện:");
      if (title) {
        const newEvent = {
          title,
          start: info.dateStr,
          resourceId: "1" // Gán sự kiện vào sân 1 (hoặc sân bạn muốn)
        };
        calendarInstance.addEvent(newEvent);

        // Lưu sự kiện vào localStorage
        const events = calendarInstance.getEvents();
        saveEventsToLocalStorage(events);
      }
    },
    eventClick: function (info) {
      // Ngăn không cho xóa các sự kiện cố định
      if (!info.event.extendedProps.editable) {
        alert("Bạn không thể chỉnh sửa sự kiện này!");
        return;
      }

      if (confirm(`Bạn có muốn xóa sự kiện "${info.event.title}"?`)) {
        info.event.remove();
        // Cập nhật lại events trong localStorage
        const events = calendarInstance.getEvents();
        saveEventsToLocalStorage(events);
      }
    },
    // Đảm bảo sự kiện được liên kết với các tài nguyên đúng
    eventContent: function (info) {
      console.log("Rendered Event: ", info.event); // Debug log để kiểm tra sự kiện có được render đúng không
    },
  });

  // Sau khi render, nạp lại các sự kiện đã lưu trong localStorage
  calendarInstance.render();

});

</script>

<template>
  <div class="calendar" ref="calendar"></div>
</template>

<style>
  .fc-button {
    padding: 8px !important;
    font-size: 1.6rem !important;
  }
  .fc-resourceTimeGridWeek-button {
    display: none !important;
  }
  .fc-dayGridMonth-button {
    display: none !important;
  }
</style>
