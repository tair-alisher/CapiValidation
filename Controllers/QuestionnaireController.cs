using System.Threading.Tasks;
using CapiValidation.Services.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace CapiValidation.Controllers
{
    public class QuestionnaireController : Controller
    {
        private readonly IQuestionnaireService _quesService;

        public QuestionnaireController(IQuestionnaireService questService)
            => _quesService = questService;

        public async Task<IActionResult> Index()
            => View(await _quesService.GetAllAsync());
    }
}