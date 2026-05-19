/**
 * MetaMyKad - Multimedia Student Registry System
 * Frontend Prototype Logic
 */

// --- Global State ---
let uploadedFilesCount = 0;
let registrationHistory = [
    { name: "Ali Bin Abu", ic: "020512-10-5431", email: "ali@student.edu.my", gender: "Male", state: "Selangor", files: 4 },
    { name: "Siti Aminah", ic: "981122-01-5220", email: "siti@gmail.com", gender: "Female", state: "Johor", files: 2 }
];

const badges = ["Pendaftar", "Pelajar", "Aktif", "Dedikasi", "Cemerlang"];

const stateCodes = {
    '01': 'Johor', '21': 'Johor', '22': 'Johor', '23': 'Johor', '24': 'Johor',
    '02': 'Kedah', '25': 'Kedah', '26': 'Kedah', '27': 'Kedah',
    '03': 'Kelantan', '28': 'Kelantan', '29': 'Kelantan',
    '04': 'Melaka', '30': 'Melaka',
    '05': 'Negeri Sembilan', '31': 'Negeri Sembilan',
    '06': 'Pahang', '32': 'Pahang', '33': 'Pahang',
    '07': 'Pulau Pinang', '34': 'Pulau Pinang', '35': 'Pulau Pinang',
    '08': 'Perak', '36': 'Perak', '37': 'Perak', '38': 'Perak', '39': 'Perak',
    '09': 'Perlis', '40': 'Perlis', '41': 'Perlis', '42': 'Perlis', '43': 'Perlis', '44': 'Perlis',
    '10': 'Selangor', '45': 'Selangor', '46': 'Selangor', '47': 'Selangor', '48': 'Selangor', '49': 'Selangor',
    '11': 'Terengganu', '50': 'Terengganu', '51': 'Terengganu', '52': 'Terengganu', '53': 'Terengganu',
    '12': 'Sabah', '54': 'Sabah', '55': 'Sabah', '56': 'Sabah', '57': 'Sabah', '58': 'Sabah',
    '13': 'Sarawak', '59': 'Sarawak',
    '14': 'Kuala Lumpur', '15': 'Labuan', '16': 'Putrajaya'
};

// --- Initialization ---
document.addEventListener('DOMContentLoaded', () => {
    setupNavigation();
    setupFormListeners();
    setupFileUploads();
    updateBadge();
    renderHistory();
});

// --- Navigation Logic ---
function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.view-section');

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href').substring(1);
            
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            sections.forEach(sec => {
                sec.classList.remove('active');
                if(sec.id === targetId) sec.classList.add('active');
            });
        });
    });
}

// --- MyKad & Email Logic ---
function setupFormListeners() {
    const icInput = document.getElementById('ic_number');
    const emailInput = document.getElementById('email');
    const icFeedback = document.getElementById('ic-feedback');
    const emailFeedback = document.getElementById('email-feedback');

    // IC Recognition
    icInput.addEventListener('input', (e) => {
        let val = e.target.value.replace(/[^0-9]/g, '');
        if (val.length > 12) val = val.substring(0, 12);
        
        // Auto-format with dashes if user typed 12 digits
        if(val.length === 12) {
             const formatted = `${val.substring(0,6)}-${val.substring(6,8)}-${val.substring(8,12)}`;
             processIC(val, icFeedback);
        } else {
            icFeedback.innerHTML = '';
        }
    });

    // Email Classification
    emailInput.addEventListener('input', (e) => {
        const email = e.target.value;
        if(email.includes('@') && email.includes('.')) {
            const category = classifyEmail(email);
            emailFeedback.innerHTML = `Category: <span class="text-emerald">${category}</span>`;
        } else {
            emailFeedback.innerHTML = '';
        }
    });
}

function processIC(ic, feedbackElement) {
    if (ic.length !== 12) return;

    // Extraction
    const year = ic.substring(0, 2);
    const month = ic.substring(2, 4);
    const day = ic.substring(4, 6);
    const placeCode = ic.substring(6, 8);
    const genderDigit = ic.substring(11, 12);

    // DOB logic (assuming students are likely 19xx or 20xx based on year digits)
    const currentYear = new Date().getFullYear() % 100;
    const century = parseInt(year) <= currentYear ? "20" : "19";
    const dob = `${day}/${month}/${century}${year}`;

    const state = stateCodes[placeCode] || "Unknown State";
    const gender = parseInt(genderDigit) % 2 === 0 ? "Female" : "Male";

    feedbackElement.innerHTML = `
        <div class="mt-4">
            <strong>Detected:</strong> ${dob} | ${gender} | ${state}
        </div>
    `;
}

function classifyEmail(email) {
    const lower = email.toLowerCase();
    if (lower.includes('.edu') || lower.includes('student')) return "Student Email";
    if (lower.includes('.com') || lower.includes('gmail') || lower.includes('yahoo')) return "Personal Email";
    if (lower.includes('.org') || lower.includes('.gov') || lower.includes('office')) return "Work/Org Email";
    return "Others";
}

// --- File Upload & Metadata ---
function setupFileUploads() {
    const uploadBoxes = document.querySelectorAll('.upload-box');
    const metadataContainer = document.getElementById('metadata-container');

    uploadBoxes.forEach(box => {
        // Click to upload
        box.addEventListener('click', () => simulateUpload(box, metadataContainer));

        // Drag and Drop events
        box.addEventListener('dragover', (e) => {
            e.preventDefault();
            box.classList.add('drag-over');
        });

        box.addEventListener('dragleave', () => {
            box.classList.remove('drag-over');
        });

        box.addEventListener('drop', (e) => {
            e.preventDefault();
            box.classList.remove('drag-over');
            simulateUpload(box, metadataContainer);
        });
    });
}

function simulateUpload(box, container) {
    const type = box.dataset.type;
    const mockFiles = {
        photo: { name: "passport_photo.jpg", mime: "image/jpeg", size: "1.2 MB", icon: "🖼️", metaType: "IMAGE" },
        audio: { name: "intro_recording.mp3", mime: "audio/mpeg", size: "4.5 MB", icon: "🎵", metaType: "AUDIO" },
        pdf: { name: "transcript_final.pdf", mime: "application/pdf", size: "850 KB", icon: "📄", metaType: "PDF" },
        video: { name: "intro_video.mp4", mime: "video/mp4", size: "24.0 MB", icon: "🎬", metaType: "VIDEO" }
    };

    const file = mockFiles[type];
    
    // Add extraction visual feedback to the upload box
    const originalContent = box.innerHTML;
    box.innerHTML = `
        <div class="upload-icon">${file.icon}</div>
        <div class="text-emerald" style="font-size: 9px; font-weight: bold; margin-top: 5px;">EXTRACTING...</div>
        <div class="extract-progress" style="display: block;">
            <div class="extract-progress-inner" style="width: 100%;"></div>
        </div>
    `;

    setTimeout(() => {
        box.innerHTML = originalContent;
        addFileCard(file, container);
        uploadedFilesCount++;
        updateBadge();
        
        // Show success mini notification
        const header = document.querySelector('header h1');
        const check = document.createElement('span');
        check.className = 'success-check visible';
        check.innerHTML = '✓';
        header.appendChild(check);
        setTimeout(() => check.remove(), 2000);
    }, 1200);
}

function addFileCard(file, container) {
    const card = document.createElement('div');
    card.className = 'file-card scanning-container';
    const date = new Date().toLocaleDateString();
    
    // Add scanning line for visual effect
    const scanLine = document.createElement('div');
    scanLine.className = 'scan-line';
    card.appendChild(scanLine);

    let extraVisual = '';
    if (file.metaType === 'AUDIO') {
        extraVisual = `
            <div class="audio-waves">
                <div class="wave-bar"></div><div class="wave-bar"></div>
                <div class="wave-bar"></div><div class="wave-bar"></div>
                <div class="wave-bar"></div>
            </div>
        `;
    }
    
    card.innerHTML += `
        <div class="file-preview">
            ${file.icon}
            ${extraVisual}
        </div>
        <div class="file-info">
            <div class="text-cyan" style="font-size: 8px; font-weight: 800; margin-bottom: 4px;">${file.metaType}_METADATA</div>
            <div class="file-name">${file.name}</div>
            <div class="file-data">
                MIME: ${file.mime}<br>
                SIZE: ${file.size}<br>
                LOADED: ${date}
            </div>
        </div>
    `;
    
    container.insertBefore(card, container.firstChild);
    
    // Remove scanning line after some time
    setTimeout(() => {
        scanLine.style.opacity = '0';
        setTimeout(() => scanLine.remove(), 500);
    }, 4000);
}

// --- Validation Animation ---
window.submitForm = function() {
    const nameInput = document.getElementById('full_name');
    const icInput = document.getElementById('ic_number');
    
    if(!nameInput.value || icInput.value.replace(/-/g, '').length < 12) {
        const card = nameInput.closest('.card');
        card.classList.add('shake');
        setTimeout(() => card.classList.remove('shake'), 400);
        alert("Extraction failed: Incomplete registry credentials detected.");
        return;
    }
    alert("CRITICAL: Student data successfully committed to MetaMyKad Registry.");
};
