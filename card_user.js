document.addEventListener('DOMContentLoaded', function() {

    const createNoteBtn = document.getElementById('create-note-btn');
    const noteModal = document.getElementById('note-modal');
    const closeModal = document.querySelector('#note-modal .close-modal');
    const noteForm = document.getElementById('note-form');
    const notesContainer = document.getElementById('notes-container');
    const noteModalTitle = document.getElementById('note-modal-title');
    const saveNoteBtn = document.getElementById('save-note-btn');
    

    const noteTitle = document.getElementById('note-title');
    const noteContent = document.getElementById('note-content');
    const editNoteId = document.getElementById('edit-note-id');
    
    const titleError = document.getElementById('titleError');
    const contentError = document.getElementById('contentError');
    
    loadNotes();
    

    createNoteBtn.addEventListener('click', openCreateModal);
    closeModal.addEventListener('click', closeNoteModal);
    noteForm.addEventListener('submit', saveNote);
    
    function openCreateModal() {
        noteModalTitle.textContent = 'Izveidot jaunu kartiņu';
        noteForm.reset();
        editNoteId.value = '';
        saveNoteBtn.textContent = 'Saglabāt';
        noteModal.style.display = 'block';
    }
    
    function openEditModal(note) {
        noteModalTitle.textContent = 'Labot kartiņu';
        noteTitle.value = note.title;
        noteContent.value = note.content;
        editNoteId.value = note.id;
        saveNoteBtn.textContent = 'Atjaunināt';
        noteModal.style.display = 'block';
    }
    
    function closeNoteModal() {
        noteModal.style.display = 'none';
        hideErrors();
    }
    
    function hideErrors() {
        titleError.classList.remove('show');
        contentError.classList.remove('show');
        noteTitle.classList.remove('error-field');
        noteContent.classList.remove('error-field');
    }
    
    function saveNote(e) {
        e.preventDefault();
        
        const title = noteTitle.value.trim();
        const content = noteContent.value.trim();
        const id = editNoteId.value || Date.now().toString();
        
        const note = {
            id: id,
            title: title,
            content: content,
            createdAt: new Date().toISOString()
        };
        
        saveNoteToStorage(note);
        renderNotes();
        closeNoteModal();

    }

    function saveNoteToStorage(note) {
        const notes = getNotesFromStorage();
        const existingIndex = notes.findIndex(n => n.id === note.id);
        
        if (existingIndex !== -1) {
            notes[existingIndex] = note;
        } else {
            notes.push(note);
        }
        
        localStorage.setItem('userNotes', JSON.stringify(notes));
    }
    
    function getNotesFromStorage() {
        const notes = localStorage.getItem('userNotes');
        return notes ? JSON.parse(notes) : [];
    }
    
    function loadNotes() {
        renderNotes();
    }
    
    function renderNotes() {
        const notes = getNotesFromStorage();
        notesContainer.innerHTML = '';
                
        notes.forEach(note => {
            const noteElement = createNoteElement(note);
            notesContainer.appendChild(noteElement);
        });
    }
    
    function createNoteElement(note) {
        const noteDiv = document.createElement('div');
        noteDiv.className = 'note-card';
        noteDiv.innerHTML = `
            <h3>${escapeHtml(note.title)}</h3>
            <p>${escapeHtml(note.content)}</p>
            <div class="note-actions">
                <button class="note-btn edit-btn" data-id="${note.id}">Labot</button>
                <button class="note-btn delete-btn" data-id="${note.id}">Dzēst</button>
            </div>
        `;
        

        const editBtn = noteDiv.querySelector('.edit-btn');
        const deleteBtn = noteDiv.querySelector('.delete-btn');
        
        editBtn.addEventListener('click', () => {
            const noteToEdit = getNotesFromStorage().find(n => n.id === note.id);
            if (noteToEdit) {
                openEditModal(noteToEdit);
            }
        });
        
        deleteBtn.addEventListener('click', () => {
            if (confirm('Vai tiešām vēlaties dzēst šo kartiņu?')) {
                deleteNote(note.id);
            }
        });
        
        return noteDiv;
    }
    
    function deleteNote(noteId) {
        const notes = getNotesFromStorage();
        const updatedNotes = notes.filter(note => note.id !== noteId);
        localStorage.setItem('userNotes', JSON.stringify(updatedNotes));
        renderNotes();
    }
    
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});