<template>
    <div class="conversation">
      <h1 v-if="globalmode">Global Chat Room</h1>  
      <h1 v-else>{{contact ? contact.name : 'Select a Contact'}}</h1>
        <template v-if="contact">
            <MessagesFeed :user="user" :contact="contact" :messages="messages" :globalmode="globalmode"></MessagesFeed>
            <MessagesComposer :user="user" :contact="contact" @send="sendMessage" @global="switchGlobal"></MessagesComposer>
        </template>
    </div>
</template>

<script>
    import MessagesFeed from './MessagesFeed';
    import MessagesComposer from './MessagesComposer';

    export default {
        props:{
            contact: {
                type: Object,
                default: null
            },
            messages: {
                type: Array,
                default: []
            },
            user:{
                type:Object,
                required:true
            }
        },
        data() {
            return {
                globalmode:false,
                //replypart
                //updatareply
                replyStatus:false,
                replyText:'',
                messageText:'',
                replyTextId:'',
            };
        },
        components:{
            MessagesFeed,
            MessagesComposer
        },
        methods:{
            sendMessage(text){
            if (!this.contact) {
                    return;
                }
                else if(this.replyStatus){
                    axios.post('/conversation/send', {
                    replyTextId:this.replyTextId,       
                    replyText:this.replyText,
                    // messageText:this.messageText, 
                    // replyStatus:this.replyStatus,

                    contact_id: this.contact.id,
                    text: text,
                    }).then((response) => {
                    this.$emit('new', response.data);
                    }) 
                }else{
                    axios.post('/conversation/send', {
                    contact_id: this.contact.id,
                    text: text,
                    }).then((response) => {
                    this.$emit('new', response.data);
                    }) 
                }
            },
            switchGlobal(){
                this.globalmode= !this.globalmode;
            },
            // getReply(draggingText){
            //     this.reply  = draggingText; 
            // }
        },
         created(){
                this.$eventBus.$on('sendReply', (sendreply,reply,message,replyId) => {
                this.replyStatus = sendreply;
                this.replyText = reply;
                this.messageText = message;
                this.replyTextId = replyId
            });
        },
    }
</script>

<style lang="scss" scoped>
.conversation{
    flex:5;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: lightgrey;
    h1 {
        font-size: 20px;
        padding: 10px;
        margin: 0;
        border-bottom: 1px dashed lightgrey; 
    }
}
</style>
