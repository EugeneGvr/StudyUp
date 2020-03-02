<template>
    <div>
        <form @submit.prevent="submit">
            <div class="flex justify-between items-center">
                <h1 class="mb-8 font-bold text-3xl">
                    <inertia-link class="text-indigo-light hover:text-indigo-dark" :href="route('admin.questions')">
                        {{$t('Questions')}}
                    </inertia-link>
                    <span class="text-indigo-light font-medium">/</span> {{$t('Add')}}
                </h1>
                <div class="p-3 border-t border-grey-lighter flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-indigo" type="submit">
                        Add Question
                    </loading-button>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-12 m-2">
                        <textarea-input v-model="text" :errors="$page.errors.text" class="pb-2 w-full" :label="$t('Text')"/>
                        <select-input
                            v-model="subject_id"
                            :errors="$page.errors.theme_id"
                            class="pb-3 w-full" :label="$t('Subject')"
                        >
                            <md-option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                                {{subject.name}}
                            </md-option>
                        </select-input>
                        <select-input
                            v-if="themes !== null"
                            v-model="theme_id"
                            :errors="$page.errors.theme_id"
                            class="pb-3 w-full" :label="$t('Theme')"
                        >
                            <md-option v-for="theme in themes" :key="theme.id" :value="theme.id">
                                {{theme.name}}
                            </md-option>
                        </select-input>
                        <select-input
                            v-if="subthemes !== null"
                            v-model="subtheme_id"
                            :errors="$page.errors.subtheme_id"
                            class="pb-3 w-full" :label="$t('Subtheme')"
                        >
                            <md-option v-for="subtheme in subthemes" :key="subtheme.id" :value="subtheme.id">
                                {{subtheme.name}}
                            </md-option>
                        </select-input>
                        <text-input type="number" min="1" max="10" v-model="level" :errors="$page.errors.level" class="pb-2 w-full" :label="$t('Level')"/>
                        <select-input
                            v-model="answer_type"
                            :errors="$page.errors.answer_type"
                            class="pb-3 w-full" :label="$t('Type')"
                        >
                            <md-option v-for="(type, type_key) in answer_types" :key="type_key" :value="type_key">
                                {{type}}
                            </md-option>
                        </select-input>
                    </div>
                </div>
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-12 m-2">
                        <file-input v-model="photo" :errors="$page.errors.photo" class="pb-4 w-full"
                                    label="Photo"/>
                    </div>
                </div>
            </div>
            <div v-if="answer_type != null" class="flex bg-white rounded shadow p-12 m-2 overflow-hidden w-full">
                <text-input
                    v-if="answer_type=='input'"
                    v-model="answer"
                    :errors="$page.errors.text"
                    class="pr-6 pb-8 w-full lg:w-full"
                    :label="$t('Correct answer')"
                />
                <table
                    v-if="answer_type=='multi' || answer_type=='single'"
                    class=" table-module w-full whitespace-no-wrap"
                >
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">{{$t('Correct')}}</th>
                        <th class="px-6 pt-6 pb-4">{{$t('Answer')}}</th>
                        <th class="px-6 pt-6 pb-4"></th>
                    </tr>
                    <tr
                        v-for="(answers_element, answer_key) in answers" :key="answer_key"
                        class="hover:bg-grey-lightest focus-within:bg-grey-lightest"
                    >
                        <td v-if="answer_type=='multi'" class="border-t">
                            <md-checkbox class="md-primary mr-2"
                                         v-model="answers_element.correct"
                                         :value= "true"
                                         :ref="answer_key"
                            ></md-checkbox>
                        </td>
                        <td v-if="answer_type=='single'" class="border-t">
                            <md-radio class="md-primary mr-2"
                                         v-model="correct_id"
                                         :value= "answer_key"
                                         :ref="answer_key"
                            ></md-radio>
                        </td>
                        <td class="border-t">
                            <div class="px-6 py-4 flex items-center focus:text-indigo">
                                {{answers_element.text}}
                            </div>
                        </td>
                        <td class="border-t">
                            <div class="btn-red mr-2" @click="deleteAnswer(answer_key)">
                                <span>{{$t('Delete')}}</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-grey-lightest focus-within:bg-grey-lightest">
                        <td class="border-t"></td>
                        <td class="border-t">
                            <div class="px-6 py-4 flex items-center focus:text-indigo">
                                <text-input
                                    v-model="answer"
                                    class="pr-6 pb-8 w-full lg:w-full"
                                    :label="$t('Answer')"
                                />
                            </div>
                        </td>
                        <td class="border-t">
                            <div class="btn-blue mr-2" @click="addAnswer()">
                                <span>{{$t('Add')}}</span>
                            </div>
                        </td>
                    </tr>
                </table>
                <table
                    v-if="answer_type=='correlation'"
                    class=" table-module w-full whitespace-no-wrap"
                >
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">{{$t('Answer')}}</th>
                        <th class="px-6 pt-6 pb-4">{{$t('Answer')}}</th>
                        <th class="px-6 pt-6 pb-4"></th>
                    </tr>
                    <tr
                        v-for="(answers_element, answer_key) in answers" :key="answer_key"
                        class="hover:bg-grey-lightest focus-within:bg-grey-lightest"
                    >
                        <td class="border-t">
                            <div class="px-6 py-4 flex items-center focus:text-indigo">
                                {{answers_element.text1}}
                            </div>
                        </td>
                        <td class="border-t">
                            <div class="px-6 py-4 flex items-center focus:text-indigo">
                                {{answers_element.text2}}
                            </div>
                        </td>
                        <td class="border-t">
                            <div class="btn-red mr-2" @click="deleteAnswer(answer_key)">
                                <span>{{$t('Delete')}}</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-grey-lightest focus-within:bg-grey-lightest">
                        <td class="border-t">
                            <div class="px-6 py-4 flex items-center focus:text-indigo">
                                <text-input
                                    v-model="answer_corr.text1"
                                    class="pr-6 pb-8 w-full lg:w-full"
                                    :label="$t('Answer')"
                                />
                            </div>
                        </td>
                        <td class="border-t">
                            <div class="px-6 py-4 flex items-center focus:text-indigo">
                                <text-input
                                    v-model="answer_corr.text2"
                                    class="pr-6 pb-8 w-full lg:w-full"
                                    :label="$t('Answer')"
                                />
                            </div>
                        </td>
                        <td class="border-t">
                            <div class="btn-blue mr-2" @click="addAnswer()">
                                <span>{{$t('Add')}}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout'
    import LoadingButton from '@/Shared/LoadingButton'
    import SelectInput from '@/Shared/SelectInput'
    import TextInput from '@/Shared/TextInput'
    import TextareaInput from '@/Shared/TextareaInput'
    import FileInput from '@/Shared/FileInput'
    import axios from 'axios'

    export default {
        metaInfo: {title: 'Create Question'},
        layout: (h, page) => h(Layout, [page]),
        components: {
            LoadingButton,
            SelectInput,
            TextInput,
            TextareaInput,
            FileInput,
        },
        props: {
            subjects: Array,
            answer_types: Object,
        },
        data() {
            return {
                themes: null,
                subthemes: null,
                answer: '',
                answer_corr: {
                    text1 : '',
                    text2 : '',
                },
                sending: false,
                //form variables
                text: null,
                subject_id: null,
                theme_id: null,
                subtheme_id: null,
                answer_type: null,
                photo: null,
                answers: [],
                correct_id: null,
                level: null,
                //form variables
            }
        },
        watch: {
            subject_id() {
                this.themes = null;
                this.theme_id = null;
                axios.get(this.route('api.v1.themes', this.subject_id))
                    .then(response => {
                        this.themes = response.data;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            theme_id() {
                this.subthemes = null;
                this.subtheme_id = null;
                axios.get(this.route('api.v1.subthemes', this.theme_id))
                    .then(response => {
                        this.subthemes = response.data;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
        },
        methods: {
            submit() {
                this.sending = true;

                if (this.answer_type === 'single') {
                    for (let key in this.answers) {
                        if (this.correct_id == key) this.answers[key].correct = true;
                    }
                }
                const form = {
                    'text': this.text,
                    'subtheme_id': this.subtheme_id,
                    'level': this.level,
                    'answer_type': this.answer_type,
                    'answers': this.answers,
                    'photo': this.photo,
                };
                this.$inertia.post(this.route('admin.questions.store'), form)
                    .then(() => this.sending = false)
            },
            addAnswer() {
                let answer_element=[];
                answer_element = this.answer_type === 'correlation' ?
                    {'text1': this.answer_corr.text1, 'text2': this.answer_corr.text2} :
                    {'correct': false, 'text':this.answer};
                this.answers.push(answer_element);
                this.answer = '';
            },
            deleteAnswer(answer_key) {
                this.answers.splice(answer_key, 1);
            },
        },
    }
</script>
