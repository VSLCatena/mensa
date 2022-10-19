<template>
  <v-card>
    <v-toolbar>
      <v-tabs v-model="tabSelected">
        <v-tabs-slider></v-tabs-slider>
        <v-tab :key="1">
          <v-icon>mdi-calendar</v-icon>
        </v-tab>
        <v-tab :key="2">
          <v-icon>mdi-clock-outline</v-icon>
        </v-tab>
      </v-tabs>
    </v-toolbar>

    <v-tabs-items v-model="tabSelected">
      <v-tab-item :key="1">
        <v-date-picker
          v-model="selectedDate"
          no-title
        ></v-date-picker>
      </v-tab-item>
      <v-tab-item :key="2">
        <v-time-picker
          v-model="selectedTime"
          format="24hr"
          no-title
        ></v-time-picker>
      </v-tab-item>
    </v-tabs-items>
    <v-card-text class="text-center">{{ dateText }}</v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn text @click="onSaveClicked()">{{ $ll($lang.text.general.save) }}</v-btn>
      <v-btn text @click="onCloseClicked()">{{ $ll($lang.text.general.cancel) }}</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
  import Vue, {PropType} from 'vue';
  import {formatDate} from "../../formatters/DateFormatter";

  export default Vue.extend({
    props: {
      onDateTimePicked: {
        type: Function,
        required: true
      },
      onClose: {
        type: Function,
        required: true
      },
      originalDate: {
        type: Date as PropType<Date>,
        required: false
      }
    },
    data: function () {
      return {
        tabSelected: 0,
        selectedDate: "",
        selectedTime: ""
      }
    },
    watch: {
      originalDate: {
        immediate: true,
        handler: function () {
          this.reset();
        }
      }
    },
    methods: {
      reset: function () {
        let date = this.originalDate;
        this.selectedDate = this.pad(date.getFullYear(), 4) + "-" +
          this.pad(date.getMonth() + 1, 2) + "-" + this.pad(date.getDate(), 2);
        this.selectedTime = this.pad(date.getHours(), 2) + ":" + this.pad(date.getMinutes(), 2);
      },
      onSaveClicked: function () {
        this.onDateTimePicked(this.newDate);
        this.onClose();
      },
      onCloseClicked: function () {
        this.reset();
        this.onClose();
      },
      pad: function (text: any, num: number): string {
        let newText = text.toString();
        while (num > newText.length) {
          newText = '0' + newText;
        }
        return newText
      }
    },
    computed: {
      newDate: function (): Date {
        let date = new Date();
        if (this.selectedDate != "" && this.selectedTime != "") {
          date = new Date(Date.parse(this.selectedDate + " " + this.selectedTime));
        }
        return date;
      },
      dateText: function (): string {
        return formatDate(this.newDate);
      }
    }
  });
</script>
