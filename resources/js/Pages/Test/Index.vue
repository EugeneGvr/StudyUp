<template>
    <div>
        <div v-if="question == null">
            <md-steppers :md-active-step.sync="active" md-linear>
                <md-step id="first" md-label="First Step" md-description="Optional" :md-done.sync="first">
                    <md-button class="md-raised md-primary" @click="setTestField('subject', subject)">Subject test</md-button>
                    <div
                        v-for="theme in themes" :key="theme.id"
                        @click="setTestField('theme', theme.id)"
                    >
                        {{ theme.name}}
                    </div>
                </md-step>
                <md-step id="second" md-label="Second Step" :md-done.sync="second">
                    <md-button class="md-raised md-primary" @click="setTestType('fixed')">Subject test</md-button>
                    <md-button class="md-raised md-primary" @click="setTestType('first_wrong', subject)">Subject test</md-button>
                </md-step>
            </md-steppers>
        </div>
        <div v-else>
            <h2>{{ question.text}}</h2>
            <div v-if="question.answer_type == 'input'">
                <text-input
                    v-model="answer"
                    class="pr-6 pb-8 w-full lg:w-full"
                    :label="$t('Answer')"
                />
            </div>
            <div v-else-if="question.answer_type == 'single'">
                <div v-for="answers_element in question.answers" :key="answers_element.id">
                    <md-radio class="md-primary mr-2"
                              v-model="answer"
                              :value= "answers_element.id"
                              :ref="answers_element.id"
                    >{{answers_element.text}}</md-radio>
                </div>
            </div>
            <div v-else-if="question.answer_type == 'multi'">
                <div v-for="answers_element in question.answers" :key="answers_element.id">
                    <md-checkbox class="md-primary mr-2"
                                 v-model="answer"
                                 :value= "answers_element.id"
                                 :ref="answers_element.id"
                    >{{answers_element.text}}</md-checkbox>
                </div>
            </div>
            <div v-if="question.answer_type == 'correlation'">
                <div v-for="answers_element in question.answers" :key="answers_element.id">

                </div>
            </div>
            <md-button class="md-raised md-primary" @click="submitAnswer()">{{$t('Submit')}}</md-button>
        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import axios from 'axios'

export default {
    metaInfo: {
        title: 'Home',
    },
    layout: (h, page) => h(Layout, [page]),
    components: {
        Icon,
    },
    props: {
        themes: Array,
        subject: String,
    },
    data() {
        return {
            active: 'first',
            first: false,
            second: false,

            testData : {
                field: null,
                id: null,
                type: null,
            },
            question: null,
            answer: null,
            testSession: [],
        }
    },
    methods: {
        setTestField (field, id) {
            this.first = true;
            this.active = 'second';

            this.testData.field = field;
            this.testData.id = id;

        },
        setTestType (type) {
            this.testData.type = type;
            this.startTest();

        },
        startTest() {
            axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            };
            axios.post(this.route('api.v1.get-question'), this.testData)
                .then(response => {
                    this.question = response.data;
                })
                .catch(e => {
                    this.errors.push(e)
                })
        },
        submitAnswer() {
            axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            };
            axios.post(this.route('api.v1.answer-question'), this.answer)
                .then(response => {
                    console.log(this.answer);
                    console.log(response.data);
                })
                .catch(e => {
                    this.errors.push(e)
                })
        },
    },
}
</script>
