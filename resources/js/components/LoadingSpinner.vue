<template>
    <div
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
</template>

<style scoped>
.dots {
    display: flex;
    align-items: center;
    justify-content: center;
}

.dot {
    width: 15px;
    height: 15px;
    margin: 0 5px;
    background-color: #fff;
    border-radius: 50%;
    animation: dotPulse 1.5s infinite ease-in-out;
}

.dot:nth-child(1) {
    animation-delay: 0s;
}

.dot:nth-child(2) {
    animation-delay: 0.3s;
}

.dot:nth-child(3) {
    animation-delay: 0.6s;
}

@keyframes dotPulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(0.5);
        opacity: 0.5;
    }
}
</style>