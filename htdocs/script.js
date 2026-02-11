const library = {
    male: {
        low: [
            { title: "Brisk Walking", tag: "Cardio", icon: "👟", videoId: "OaWSeXpI_G0", desc: "Walking for Heart Health." },
            { title: "Push Up Form", tag: "Strength", icon: "💪", videoId: "IODxDxX7oi4", desc: "Mastering the perfect pushup." }
        ],
        medium: [
            { title: "Kettlebell Swing", tag: "Power", icon: "🔔", videoId: "sSESeN_S5S4", desc: "CrossFit® - Official Kettlebell Swing Tutorial." },
            { title: "Standard Burpee", tag: "Aerobic", icon: "⏱️", videoId: "auBLPXO8FHI", desc: "Standard Burpee mechanics from CrossFit®." }
        ],
        high: [
            { title: "Sprinting Drills", tag: "HIIT", icon: "🏃", videoId: "6B3_7v6P9r4", desc: "Elite sprinting form for maximum speed." },
            { title: "Thrusters", tag: "Peak", icon: "🏋️", videoId: "L219ltL15zk", desc: "High-intensity full body power movement." }
        ]
    },
    female: {
        low: [
            { title: "Yoga for Flow", tag: "Flex", icon: "🧘", videoId: "v7AYKMP6rOE", desc: "Yoga With Adriene - Yoga for Beginners." },
            { title: "Plank Basics", tag: "Core", icon: "🧱", videoId: "pSHjTRCQxIw", desc: "Perfecting your plank alignment." }
        ],
        medium: [
            { title: "Pilates Routine", tag: "Stability", icon: "🤸", videoId: "K-PpD69it_k", desc: "Move with Nicole - Core Pilates." },
            { title: "Jump Rope", tag: "Cardio", icon: "➰", videoId: "u3zgHI8Qnq8", desc: "Essential cardio jump rope techniques." }
        ],
        high: [
            { title: "HIIT Workout", tag: "Peak", icon: "🔥", videoId: "ml6cT4AZdqI", desc: "High intensity circuit training." },
            { title: "Cool Down Flow", tag: "Safety", icon: "🌸", videoId: "2vK7oV7bY7Y", desc: "Safely lowering heart rate with Yoga With Adriene." }
        ]
    }
};

function updateDashboardFromPHP(bpm) {
    // Get gender from dropdown, default to male if not found
    const genderInput = document.getElementById('genderInput');
    const gender = genderInput ? genderInput.value : 'male';
    
    // Determine Zone
    let zoneKey = (bpm > 130) ? "high" : (bpm >= 80 ? "medium" : "low");
    let zoneLabel = (bpm > 130) ? "Peak" : (bpm >= 80 ? "Aerobic" : "Optimal");
    let zoneColor = zoneKey === "high" ? "#e7332b" : (zoneKey === "medium" ? "#f59e0b" : "#10b981");

    // Update Text Elements
    const avgBpmEl = document.getElementById('avgBpm');
    const zoneTxtEl = document.getElementById('zoneTxt');
    
    if(avgBpmEl) avgBpmEl.innerText = bpm;
    if(zoneTxtEl) zoneTxtEl.innerText = zoneLabel;
    
    // Update Card Styling
    const statusCard = document.getElementById('statusCard');
    const statusIcon = document.getElementById('statusIcon');
    if(statusCard) statusCard.style.borderLeft = `5px solid ${zoneColor}`;
    if(statusIcon) {
        statusIcon.innerHTML = zoneKey === "high" ? "🔥" : (zoneKey === "medium" ? "⚡" : "✅");
        statusIcon.style.color = zoneColor;
    }

    // Recommendation Grid Update
    const grid = document.getElementById('recGrid');
    if(grid) {
        grid.innerHTML = ''; // Clear old content
        const exercises = library[gender][zoneKey];
        
        exercises.forEach(ex => {
            const card = document.createElement('div');
            card.className = 'card rec-card'; // Dinagdagan ng 'card' class para sa styling
            card.style.cursor = 'pointer';
            card.onclick = () => openModal(ex);
            card.innerHTML = `
                <span class="tag">${ex.tag}</span>
                <div style="font-size:32px; margin: 10px 0;">${ex.icon}</div>
                <h3 style="margin:0;">${ex.title}</h3>
            `;
            grid.appendChild(card);
        });
    }
}

function openModal(ex) {
    const modal = document.getElementById('exerciseModal');
    const videoCont = document.getElementById('videoContainer');
    if(modal && videoCont) {
        videoCont.innerHTML = `<iframe width="100%" height="315" src="https://www.youtube.com/embed/${ex.videoId}?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
        document.getElementById('modalTitle').innerText = ex.title;
        document.getElementById('modalTag').innerText = ex.tag;
        document.getElementById('modalDesc').innerText = ex.desc;
        modal.style.display = 'flex';
    }
}

function closeModal() {
    const modal = document.getElementById('exerciseModal');
    if(modal) {
        document.getElementById('videoContainer').innerHTML = '';
        modal.style.display = 'none';
    }
}