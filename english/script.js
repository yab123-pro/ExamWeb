// Util: DOM helpers
const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

// Header: mobile nav toggle
(() => {
  const toggle = $('.nav-toggle');
  const nav = $('.site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const visible = nav.style.display === 'flex';
      nav.style.display = visible ? 'none' : 'flex';
    });
    // Close menu when clicking a link on mobile
    nav.addEventListener('click', (e) => {
      if (e.target.tagName === 'A' && window.innerWidth <= 640) {
        nav.style.display = 'none';
      }
    });
  }
})();

// Footer year
(() => {
  const y = $('#year');
  if (y) y.textContent = new Date().getFullYear();
})();

// Progress tracking with localStorage
const STORAGE_KEY = 'fluentpath_progress';
function getProgress() {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY)) || { quizzes: {} };
  } catch {
    return { quizzes: {} };
  }
}
function setProgress(p) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(p));
}
function updateGlobalProgressUI() {
  const p = getProgress();
  const quizCount = 3; // total quizzes defined on lessons page
  const completed = Object.values(p.quizzes).filter(Boolean).length;
  const percent = Math.round((completed / quizCount) * 100);
  const homeLabel = $('#home-progress');
  const homeBar = $('#home-progress-bar');
  const lessonsLabel = $('#lessons-progress');
  const lessonsBar = $('#lessons-progress-bar');
  if (homeLabel) homeLabel.textContent = `${percent}%`;
  if (homeBar) homeBar.style.width = `${percent}%`;
  if (lessonsLabel) lessonsLabel.textContent = `${percent}%`;
  if (lessonsBar) lessonsBar.style.width = `${percent}%`;
}
updateGlobalProgressUI();

// Quiz logic
const QUIZ_ANSWERS = {
  'quiz-1': {
    q1: 'B',
    q2: ['are studying', 'they are studying'], // accept variations
    q3: 'False',
    explain: [
      '“She often goes” uses present simple for habits. “is often going” is unnatural.',
      'Present continuous: “They are studying at the moment.”',
      'Present simple is for habits/facts, not actions happening now.'
    ]
  },
  'quiz-2': {
    q1: 'B',
    q2: ['go'], // “I go to bed…”
    q3: 'False', // Correct collocation is “go home”
    explain: [
      'We say “have breakfast”, not “make/do breakfast”.',
      'Correct: “I go to bed at 10 p.m.”',
      'We say “go home”, not “go to home”.'
    ]
  },
  'quiz-3': {
    q1: 'B',
    q2: ['this'], // voiced /ð/
    q3: 'True',
    explain: [
      '“think” uses /θ/ (unvoiced). “then/that” are /ð/ (voiced).',
      '“This is a good idea.” uses /ð/ in “this”.',
      'The tongue lightly between the teeth helps produce both “th” sounds.'
    ]
  }
};

function normalizeInput(val) {
  return (val || '').trim().toLowerCase();
}

function checkQuiz(form, quizId) {
  const defs = QUIZ_ANSWERS[quizId];
  if (!defs) return { score: 0, total: 0, feedback: [] };

  let score = 0;
  const total = 3;
  const feedback = [];

  // Q1 radio
  const q1 = form.querySelector('input[name="q1"]:checked')?.value;
  if (q1 === defs.q1) score++; else feedback.push(`1) ${defs.explain[0]}`);

  // Q2 text (accept list)
  const q2 = normalizeInput(form.querySelector('input[name="q2"]')?.value);
  const accepted = defs.q2.map(normalizeInput);
  if (accepted.includes(q2)) score++; else feedback.push(`2) ${defs.explain[1]}`);

  // Q3 radio
  const q3 = form.querySelector('input[name="q3"]:checked')?.value;
  if (q3 === defs.q3) score++; else feedback.push(`3) ${defs.explain[2]}`);

  return { score, total, feedback };
}

function initQuizzes() {
  $$('.quiz').forEach(quiz => {
    const form = quiz.querySelector('form');
    const btn = quiz.querySelector('.quiz-submit');
    const result = quiz.querySelector('.quiz-result');
    const quizId = quiz.getAttribute('data-quiz-id');

    btn?.addEventListener('click', () => {
      const { score, total, feedback } = checkQuiz(form, quizId);
      const pct = Math.round((score / total) * 100);

      if (pct === 100) {
        result.textContent = `Great job — ${pct}% correct!`;
        result.style.color = 'var(--success)';
        const progress = getProgress();
        progress.quizzes[quizId] = true;
        setProgress(progress);
      } else {
        result.textContent = `You got ${score}/${total}. Tips: ${feedback.join(' | ')}`;
        result.style.color = 'var(--warning)';
        const progress = getProgress();
        progress.quizzes[quizId] = false;
        setProgress(progress);
      }

      updateGlobalProgressUI();
    });
  });
}
initQuizzes();

// Contact form (mock)
(() => {
  const form = $('.contact-card .form');
  const status = $('.form-status');
  if (!form) return;
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    status.textContent = 'Sending...';
    setTimeout(() => {
      status.textContent = 'Thanks! We’ll reply soon.';
      form.reset();
    }, 800);
  });
})();