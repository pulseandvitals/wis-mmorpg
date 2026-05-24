<script setup>
defineProps({
    lootDrops: Array,
});
</script>
<template>
    <div class="loot-modal">
        <div class="loot-box">
            <h3>🎁 Loots</h3>

            <div class="loot-grid">
                <div
                    v-for="(qty, item) in lootDrops"
                    :key="item"
                    class="loot-item"
                >
                    <img
                        :src="
                            item.toLowerCase().includes('card')
                                ? `/cards/${item}.png`
                                : `/materials/${item}.png`
                        "
                    />

                    <div class="loot-info">
                        <p class="loot-name">{{ item }}</p>
                        <p class="loot-qty">x{{ qty }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.loot-modal {
    position: absolute;
    inset: 0;

    background: rgba(0, 0, 0, 0.5);

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 99999;
}

.loot-box {
    width: 260px;

    background: rgba(20, 20, 20, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.08);

    border-radius: 12px;

    padding: 12px;

    color: white;

    backdrop-filter: blur(6px);
}

.loot-box h3 {
    text-align: center;
    margin-bottom: 10px;
    color: #fde047;
    font-size: 12px;
}

.loot-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.loot-item {
    display: flex;
    align-items: center;
    gap: 10px;

    padding: 6px;

    background: rgba(255, 255, 255, 0.04);
    border-radius: 8px;
}

.loot-item img {
    width: 32px;
    height: 32px;
    image-rendering: pixelated;
}

.loot-name {
    font-size: 10px;
}

.loot-qty {
    font-size: 10px;
    color: #a3e635;
}

.close-loot {
    margin-top: 10px;

    width: 100%;

    padding: 6px;

    font-size: 10px;

    border: none;
    border-radius: 6px;

    cursor: pointer;

    background: rgba(255, 255, 255, 0.08);
    color: white;
}
</style>
