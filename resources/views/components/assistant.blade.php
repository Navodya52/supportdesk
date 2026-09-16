{{-- SupportDesk Assistant — floating chat widget --}}
{{-- Available to all authenticated users; uses Alpine.js + Fetch API --}}
@php
    $quickActions = config('assistant.quick_actions', []);
    $chatRoute    = route('assistant.chat');
    $ticketRoute  = route('tickets.create');
@endphp

<div
    x-data="assistantChat('{{ $chatRoute }}', '{{ $ticketRoute }}')"
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3"
>
    {{-- Chat Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden"
        style="max-height: 560px;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-indigo-600">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm leading-tight">SupportDesk Assistant</p>
                    <p class="text-indigo-200 text-xs">Ask me about common IT issues</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button
                    @click="clearChat()"
                    title="Clear conversation"
                    class="text-indigo-200 hover:text-white p-1 rounded transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                <button
                    @click="open = false"
                    title="Close"
                    class="text-indigo-200 hover:text-white p-1 rounded transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div
            id="assistant-messages"
            class="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-gray-50"
            style="min-height: 200px; max-height: 340px;"
            x-ref="messages"
        >
            {{-- Welcome message --}}
            <template x-if="messages.length === 0">
                <div class="text-center py-2">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Hi there! 👋</p>
                    <p class="text-xs text-gray-500 mt-1">I can help you troubleshoot common IT issues. Select a topic or type your question below.</p>

                    {{-- Quick actions --}}
                    <div class="mt-3 flex flex-wrap gap-2 justify-center">
                        @foreach ($quickActions as $action)
                            <button
                                @click="sendMessage('{{ $action }}')"
                                class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-full px-3 py-1 transition-colors"
                            >
                                {{ $action }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </template>

            {{-- Message bubbles --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div
                        :class="msg.sender === 'user'
                            ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-sm px-3 py-2 text-sm max-w-[80%]'
                            : 'bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-tl-sm px-3 py-2 text-sm max-w-[90%] shadow-sm'"
                    >
                        <p x-html="msg.html" class="leading-relaxed whitespace-pre-line break-words"></p>

                        {{-- Ticket escalation button --}}
                        <template x-if="msg.showTicketButton">
                            <a
                                :href="ticketRoute"
                                class="mt-2 inline-flex items-center gap-1 text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition-colors"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"/>
                                </svg>
                                Create Support Ticket
                            </a>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <template x-if="loading">
                <div class="flex justify-start">
                    <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                        <div class="flex gap-1 items-center">
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <div class="px-3 py-3 border-t border-gray-200 bg-white">
            <form @submit.prevent="sendMessage(input)" class="flex gap-2 items-end">
                <input
                    x-model="input"
                    type="text"
                    placeholder="Ask a question..."
                    maxlength="500"
                    :disabled="loading"
                    class="flex-1 text-sm border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:opacity-60"
                    @keydown.enter.prevent="sendMessage(input)"
                />
                <button
                    type="submit"
                    :disabled="loading || !input.trim()"
                    class="shrink-0 w-9 h-9 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl flex items-center justify-center transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- FAB Toggle Button --}}
    <button
        @click="open = !open"
        class="w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95"
        :title="open ? 'Close Assistant' : 'Open SupportDesk Assistant'"
    >
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script>
function assistantChat(chatRoute, ticketRoute) {
    return {
        open: false,
        input: '',
        loading: false,
        messages: [],
        chatRoute,
        ticketRoute,

        async sendMessage(text) {
            text = (text || '').trim();
            if (!text || this.loading) return;

            this.messages.push({ sender: 'user', html: this.escapeHtml(text), showTicketButton: false });
            this.input = '';
            this.loading = true;
            this.$nextTick(() => this.scrollToBottom());

            try {
                const res = await fetch(this.chatRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: text }),
                });

                const data = await res.json();

                this.messages.push({
                    sender: 'assistant',
                    html: this.formatMarkdown(data.message || 'Sorry, I could not process that.'),
                    showTicketButton: !data.matched,
                });
            } catch {
                this.messages.push({
                    sender: 'assistant',
                    html: 'Sorry, I\'m having trouble connecting right now. Please try again or create a support ticket.',
                    showTicketButton: true,
                });
            }

            this.loading = false;
            this.$nextTick(() => this.scrollToBottom());
        },

        clearChat() {
            this.messages = [];
            this.input = '';
        },

        scrollToBottom() {
            const el = this.$refs.messages;
            if (el) el.scrollTop = el.scrollHeight;
        },

        escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        },

        formatMarkdown(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/`(.+?)`/g, '<code class="bg-gray-100 px-1 rounded text-xs font-mono">$1</code>')
                .replace(/^\d+\.\s+/gm, (m) => m)
                .replace(/\n/g, '<br>');
        },
    };
}
</script>