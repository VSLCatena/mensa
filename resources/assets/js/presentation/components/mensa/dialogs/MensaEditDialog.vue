<template>
    <v-dialog max-width="800" v-model="isOpen" transition="dialog-bottom-transition" :persistent="loading">
        <v-card outlined v-if="mensa != null">
            <v-toolbar>
                <v-card-title>{{ $ll($lang.text.mensa.edit.title) }} {{ formatDate(mensa.date) }}</v-card-title>
            </v-toolbar>
            <div class="px-3 pt-3">
                <v-form ref="mensaEditForm">
                    <v-text-field
                        :label="$ll($lang.text.mensa.edit.field_title)"
                        v-model="mensa.title"
                        :disabled="loading"
                        :rules="validations.title"
                        :counter="MAX_STRING_LENGTH"
                        hide-details="auto"
                        class="mt-8 mb-4" />

                    <v-text-field
                        :label="$ll($lang.text.mensa.edit.field_description)"
                        v-model="mensa.description"
                        :disabled="loading"
                        :rules="validations.title"
                        :counter="MAX_STRING_LENGTH"
                        hide-details="auto"
                        class="mt-8 mb-4" />

                    <v-menu
                        :close-on-content-click="false"
                        transition="scale-transition"
                        v-model="dateModal"
                        offset-y
                        min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                ref="date"
                                :label="$ll($lang.text.mensa.edit.field_date)"
                                v-model="formattedDate"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                class="mt-8 mb-4" />
                        </template>
                        <DateTimePicker
                            :on-date-time-picked="onDatePicked"
                            :on-close="() => this.dateModal = false"
                            :original-date="mensa.date" />
                    </v-menu>

                    <v-menu
                        :close-on-content-click="false"
                        transition="scale-transition"
                        v-model="closingTimeModal"
                        offset-y
                        min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                ref="closingTime"
                                :label="$ll($lang.text.mensa.edit.field_closing_time)"
                                v-model="formattedClosingTime"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                class="mt-8 mb-4" />
                        </template>
                        <DateTimePicker
                            :on-date-time-picked="onClosingTimePicked"
                            :on-close="() => this.closingTimeModal = false"
                            :original-date="mensa.closingTime" />
                    </v-menu>
                </v-form>
            </div>
            <v-card-actions>
                <v-btn text :loading="loading" @click="save()">{{ $ll($lang.text.general.save) }}</v-btn>
                <v-spacer></v-spacer>
                <v-btn text :loading="loading" @click="isOpen = false">{{ $ll($lang.text.general.close) }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script lang="ts">
import Vue from 'vue';
import Mensa from "../../../../domain/mensa/model/Mensa";
import {formatUsers} from "../../../formatters/StringFormatter";
import {AnonymousUser, SimpleUser} from "../../../../domain/common/model/User";
import {formatDate} from "../../../formatters/DateFormatter";
import {MAX_STRING_LENGTH, Validations} from "../../../utils/ValidationRules";
import DateTimePicker from "../../common/DateTimePicker.vue";
import UpdateMensa from "../../../../domain/mensa/usecase/UpdateMensa";

export default Vue.extend({
    components: {DateTimePicker},
    data: function() {
        return {
            isOpen: false,
            loading: false,
            mensa: null as Mensa|null,
            dateModal: false,
            closingTimeModal: false,
            MAX_STRING_LENGTH: MAX_STRING_LENGTH,
            validations: {
                title: [Validations.MaxStringLengthValidation],
                description: [Validations.MaxStringLengthValidation],
                date: [],
                closingTime: [],
                maxSignups: [],
                price: []
            }
        }
    },
    methods: {
        open: function (mensa: Mensa) {
            if (Number.isInteger(mensa.signups) || !this.$local.user.isAdmin) return;
            this.mensa = {...mensa};
            this.isOpen = true;
        },
        save: function () {
            let mensa = this.mensa;
            if (mensa == null) return;

            UpdateMensa(mensa)
                .then(value => {})
                .catch(error => {})
        },
        onDatePicked: function (date: Date) {
            if (this.mensa == null) return;
            this.mensa.date = date;
        },
        onClosingTimePicked: function (date: Date) {
            if (this.mensa == null) return;
            this.mensa.closingTime = date;
        },
        formatDate: formatDate
    },
    computed: {
        formattedDate: function(): string {
            let mensa = this.mensa;
            if (mensa == null) return "";
            return formatDate(mensa.date);
        },
        formattedClosingTime: function(): string {
            let mensa = this.mensa;
            if (mensa == null) return "";
            return formatDate(mensa.closingTime);
        }
    }
});
</script>
