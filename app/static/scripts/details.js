const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%*&*+=-~πΩ∞▽';

function generateMatrixStream() {
  const span = document.createElement('span');
  const randomChar = characters.charAt(Math.floor(Math.random() * characters.length));
  span.textContent = randomChar;
  return span;
}

function positionMatrixLines() { // Wrap in a function for execution timing
  const matrixLines = document.querySelectorAll('.matrix-line');
  const containerWidth = document.querySelector('.matrix-container').offsetWidth;
  const columnWidth = 15; // Adjust the width of each column
   matrixLines.forEach((line, index) => {
    line.style.left = `${index * columnWidth}px`; // Calculate left offset
     setInterval(() => {
      line.appendChild(generateMatrixStream());
      // Clean up old characters (adjust if needed)
      if (line.children.length > 100) {
        line.removeChild(line.firstChild);
      }
    }, 50 + (Math.random() * 50)); // Adjust character fall speed
  });
}

document.addEventListener('DOMContentLoaded', function() {
  positionMatrixLines(); // Call the function after the DOM is ready 
});
