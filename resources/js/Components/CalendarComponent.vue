<template>
  <div>
      <div>
        <div>
          <form @submit.prevent class="form">
            <div class=""form-container>
              <div class="form-data">
                <label for="event_name">Event Name: </label>
                <input type="text" id="event_name" v-model="newEvent.event_name" placeholder="Enter a title">
              </div>
              <div class="form-data">
                <label for="event_description">Description:</label>
                <textarea id="event_description" cols="22" rows="5" v-model="newEvent.event_description" placeholder="Enter your event description"></textarea>
              </div>
            </div>
            
            <div class="form-container">
              <div>
                <div class="form-data">
                  <label for="event_start">Start Date : </label>
                  <input
                    type="date"
                    id="event_start"
                    class="form-control"
                    v-model="newEvent.event_start"
                  >
                </div>
              </div>
              <div>
                <div class="form-data">
                  <label for="event_end">End Date : </label>
                  <input type="date" id="event_end" class="form-control" v-model="newEvent.event_end">
                </div>
              </div>
              <div v-if="addingEvent">
                <button @click="addNewEvent" class="button button-save">Save Event</button>
              </div>
              <template v-else>
                <div class="other-buttons">
                  <button class="button button-update" @click="updateEvent">Update</button>
                  <button class="button button-delete" @click="deleteEvent">Delete</button>
                  <button class="button button-cancel" @click="cancel">Cancel</button>
                </div>
              </template>
            </div>
          </form>
        </div>
        <br>
        <div>
          <FullCalendar :options="calendarOptions"/>
        </div>
    </div>
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import axios from "axios"

export default {
  components: {
    FullCalendar
  },
  data() {
    return {
      calendarOptions: {
        plugins: [ dayGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        eventClick: this.showEvent,
        events: []
      },
      addingEvent: true,
      indexToUpdate: "",
      newEvent: {
        title: "",
        event_description:"",
        event_start: "",
        event_end: ""
      }
    }
  },
  created() {
    this.getEvents();
  },
  methods: {
    addNewEvent() {
      axios
        .post("/api/calendarEvent", {
          ...this.newEvent
        })
        .then(data => {
          this.getEvents();
          this.resetForm();
        })
    },
    cancel() {
      this.addingEvent = true;
      this.resetForm();
    },
    deleteEvent() {
      axios
        .delete("/api/calendarEvent/" + this.indexToUpdate)
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.addingEvent = !this.addingEvent;
        })
    },
    getEvents() {
      axios
        .get("/api/calendarEvent/")
        .then(resp => (this.calendarOptions.events = resp.data.data))
    },
    resetForm() {
      Object.keys(this.newEvent).forEach(key => {
        return (this.newEvent[key] = "");
      });
    },
    showEvent(arg) {
      this.addingEvent = false;
      const { id, title, description, start, end } = this.calendarOptions.events.find(
        event => event.id === +arg.event.id
      );
      this.indexToUpdate = id;
      this.newEvent = {
        event_name: title,
        event_start: start,
        event_description: description,
        event_end: end
      };
    },updateEvent() {
      axios
        .put("/api/calendarEvent/" + this.indexToUpdate, {
          ...this.newEvent
        })
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.addingEvent = !this.addingEvent;
        })
    }
  },
  watch: {
    indexToUpdate() {
      return this.indexToUpdate;
    }
  }
}

</script>

<style lang="css">
  .button{
      cursor: pointer;
      border-radius: 5px;
      border-width: 0;
      color:white
  }

    .button-save{
      background-color: #0095ff;
  }

    .button-update{
      background-color: #2ea44f;
  }

  .button-delete{
      background-color: #FF4742;
  }

  .button-cancel{
      background-color: #6E6D70;
  }

  .other-buttons {
      display:flex;
      justify-content:space-between;
  }

  .form{
    display:flex;
    justify-content:space-around;
  }

  .form-data{
    display:flex;
    padding-bottom:5px;
    justify-content: space-between;
  }

  .form-container{
    display:flex;
    flex-direction:column;
    justify-content: space-between;
  }
  
  .fc-event{
      cursor: pointer;
  }

  :root {
    font-family: -apple-system,system-ui,"Segoe UI",Roboto,"Helvetica Neue",Ubuntu,sans-serif;
  }
</style>