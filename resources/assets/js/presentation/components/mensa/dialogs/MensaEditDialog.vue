<template>
    <v-dialog max-width="800" v-model="isOpen" transition="dialog-bottom-transition" :persistent="loading">
        <v-card>
            <v-toolbar>
                <v-card-title>{{ $ll($lang.text.mensa.edit.title) }} {{ formatDate(editor.date) }}</v-card-title>
                <template v-slot:extension>
                    <v-tabs v-model="tab" show-arrows>
                        <v-tabs-slider></v-tabs-slider>
                        <v-tab :key="0">{{ $ll($lang.text.mensa.edit.tabs.general)}}</v-tab>
                        <v-tab :key="1">{{ $ll($lang.text.mensa.edit.tabs.menu)}}</v-tab>
                        <v-tab :key="2">{{ $ll($lang.text.mensa.edit.tabs.prices)}}</v-tab>
                        <v-tab :key="3">{{ $ll($lang.text.mensa.edit.tabs.signups)}}</v-tab>
                    </v-tabs>
                </template>
            </v-toolbar>
            <div class="px-3 pt-3">
                <v-form ref="mensaEditForm">
                    <v-tabs-items v-model="tab">
                        <v-tab-item :key="0" class="pa-5">
                            <v-text-field
                                    :label="$ll($lang.text.mensa.edit.field_title)"
                                    v-model="editor.title"
                                    :disabled="loading"
                                    :rules="validations.title"
                                    :counter="MAX_STRING_LENGTH"
                                    hide-details="auto"
                                    class="mt-8 mb-4" />

                            <v-text-field
                                    :label="$ll($lang.text.mensa.edit.field_description)"
                                    v-model="editor.description"
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
                                        :original-date="editor.date" />
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
                                        :original-date="editor.closingTime" />
                            </v-menu>
                        </v-tab-item>
                        <v-tab-item :key="1" class="pa-5">
                            <v-list>
                                <draggable v-model="editor.menu" group="menu" handle=".handle">
                                    <template v-for="(item, index) in editor.menu">
                                        <div :key="item.draggableId">
                                            <v-list-item>
                                                <v-list-item-icon class="handle"><v-icon>mdi-menu</v-icon></v-list-item-icon>
                                                <v-text-field
                                                    :label="$ll($lang.text.mensa.edit.field_menu_label)"
                                                    v-model="item.text"
                                                    :rules="validations.menuItem" />
                                                <v-list-item-icon @click="editor.menu.splice(index, 1)" class="clickable delete-button">
                                                    <v-icon>mdi-delete</v-icon>
                                                </v-list-item-icon>
                                            </v-list-item>
                                            <v-divider v-if="index < editor.menu.length - 1"></v-divider>
                                        </div>
                                    </template>
                                </draggable>
                            </v-list>
                            <v-btn class="pa-2 icon-button" outlined @click="onAddMenuItemClicked()"><v-icon>mdi-plus-thick</v-icon></v-btn>
                        </v-tab-item>
                        <v-tab-item :key="2" class="pa-5">
                            <v-text-field
                                :label="$ll($lang.text.mensa.edit.field_base_price)"
                                type="number"
                                step="0.01"
                                v-model="editor.price" />

                            <v-list>
                                <draggable v-model="editor.extraOptions" group="extraOptions" handle=".handle">
                                    <template v-for="(item, index) in editor.extraOptions">
                                        <div :key="item.draggableId">
                                            <v-list-item>
                                                <v-list-item-icon class="handle"><v-icon>mdi-menu</v-icon></v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-text-field
                                                        :label="$ll($lang.text.mensa.edit.field_extra_option_description_label)"
                                                        v-model="item.description" />
                                                    <v-text-field
                                                        :label="$ll($lang.text.mensa.edit.field_extra_option_price_label)"
                                                        v-model="item.price" />
                                                </v-list-item-content>
                                                <v-list-item-icon @click="editor.extraOptions.splice(index, 1)" class="clickable delete-button">
                                                    <v-icon>mdi-delete</v-icon>
                                                </v-list-item-icon>
                                            </v-list-item>
                                            <v-divider v-if="index < editor.extraOptions.length - 1"></v-divider>
                                        </div>
                                    </template>
                                </draggable>
                            </v-list>
                            <v-btn class="pa-2 icon-button" outlined @click="onAddExtraOptionClicked()"><v-icon>mdi-plus-thick</v-icon></v-btn>
                        </v-tab-item>
                        <v-tab-item :key="3" class="px-5">
                            <v-list two-line>
                                <template v-for="(item, index) in signups">
                                    <v-list-item :key="item.id">
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle
                                                    class="text--primary"
                                                    v-text="item.foodPreference"
                                            ></v-list-item-subtitle>
                                        </v-list-item-content>
                                        <v-list-item-action>
                                            <v-icon class="text--secondary">mdi-square-edit-outline</v-icon>
                                        </v-list-item-action>
                                    </v-list-item>
                                    <v-divider v-if="index < mensa.signups.length - 1" :key="index"></v-divider>
                                </template>
                            </v-list>
                        </v-tab-item>
                    </v-tabs-items>
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
import {formatDate} from "../../../formatters/DateFormatter";
import {MAX_STRING_LENGTH, Validations} from "../../../utils/ValidationRules";
import DateTimePicker from "../../common/DateTimePicker.vue";
import UpdateMensa from "../../../../domain/mensa/usecase/UpdateMensa";
import draggable from 'vuedraggable';
import clone from 'just-clone';
import EditMensa, {PartialExtraOption, PartialMensaMenuItem} from "../../../../domain/mensa/model/EditMensa";
import { v4 as uuidv4 } from "uuid";
import MensaMenuItem from "../../../../domain/mensa/model/MensaMenuItem";
import ExtraOption from "../../../../domain/mensa/model/ExtraOption";
import FoodOption from "../../../../domain/mensa/model/FoodOption";
import MensaSignup from "../../../../domain/signup/model/MensaSignup";

interface TempMenuItem {
    draggableId: string,
    id?: string,
    text: string
}

interface TempExtraOption {
    draggableId: string,
    id?: string,
    description: string,
    price: number
}

export default Vue.extend({
    components: {DateTimePicker, draggable},
    data: function() {
        return {
            isOpen: false,
            loading: false,
            tab: 0,
            dateModal: false,
            closingTimeModal: false,
            MAX_STRING_LENGTH: MAX_STRING_LENGTH,
            validations: {
                title: [Validations.MaxStringLengthValidation],
                description: [Validations.MaxStringLengthValidation],
                date: [],
                closingTime: [],
                maxSignups: [],
                price: [],
                menuItem: [Validations.MaxStringLengthValidation]
            },
            editor: {
                id: null as string|null,
                title: "" as string,
                description: "" as string,
                foodOptions: [] as FoodOption[],
                menu: [] as TempMenuItem[],
                extraOptions: [] as TempExtraOption[],
                date: new Date(),
                closingTime: new Date(),
                price: 0.0,
                maxSignups: 0
            },
            signups: undefined as MensaSignup[]|undefined
        }
    },
    methods: {
        open: function (mensa: EditMensa, signups: MensaSignup[]|undefined) {
            if (!this.$local.user.isAdmin) return;

            let tempMenu = mensa.menu.map(item => {
                return {
                    draggableId: uuidv4(),
                    id: item.id,
                    text: item.text
                }
            });
            let tempExtraOptions = mensa.extraOptions.map(item => {
                return {
                    draggableId: uuidv4(),
                    id: item.id,
                    description: item.description,
                    price: item.price
                }
            });

            this.signups = signups;

            this.editor = {
                id: mensa.id ?? null,
                title: mensa.title,
                description: mensa.description,
                foodOptions: mensa.foodOptions,
                menu: tempMenu,
                extraOptions: tempExtraOptions,
                date: mensa.date,
                closingTime: mensa.closingTime,
                price: mensa.price,
                maxSignups: mensa.maxSignups
            }

            this.isOpen = true;
        },
        save: function () {
            this.loading = true;

            // TODO
            // mensa.menu = this.tempMenu?.map(item => item.item) ?? [];
            // mensa.extraOptions = this.tempExtraOptions?.map(item => item.item) ?? [];

            // UpdateMensa(mensa)
            //     .then(value => {
            //         this.loading = false;
            //         // TODO
            //     })
            //     .catch(error => {
            //         this.loading = false
            //         // TODO
            //     })
        },
        onDatePicked: function (date: Date) {
            this.editor.date = date;
        },
        onClosingTimePicked: function (date: Date) {
            this.editor.closingTime = date;
        },
        onAddMenuItemClicked: function () {
            this.editor.menu.push({
                draggableId: uuidv4(),
                text: ""
            });
        },
        onAddExtraOptionClicked: function () {
            this.editor.extraOptions.push({
                draggableId: uuidv4(),
                description: "",
                price: 0.0
            });
        },
        formatDate: formatDate
    },
    computed: {
        formattedDate: function(): string {
            return formatDate(this.editor.date);
        },
        formattedClosingTime: function(): string {
            return formatDate(this.editor.closingTime);
        }
    }
});
</script>

<style lang="css">
    .handle {
        cursor: grab;
        align-self: center;
    }
    .delete-button {
        align-self: center;
    }
    .clickable {
        cursor: pointer;
    }
    .icon-button {
        min-width: 0 !important;
    }
</style>
