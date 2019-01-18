using System.Threading.Tasks;
using CapiValidation.Data.Entities;
using CapiValidation.Models.Pagination;
using CapiValidation.Services.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace CapiValidation.Controllers
{
    public class QuestionnaireController : Controller
    {
        private readonly IQuestionnaireService _quesService;

        public QuestionnaireController(IQuestionnaireService questService)
            => _quesService = questService;

        public async Task<IActionResult> Index(int page = 1)
        {
            int pageSize = 10;
            int count = await _quesService.GetItemsAmountAsync();
            var questionnaires = await _quesService.GetPagedListAsync(page, pageSize);

            PageViewModel pageViewModel = new PageViewModel(count, page, pageSize);
            var model = new IndexViewModel<Questionnaire>
            {
                PageViewModel = pageViewModel,
                Items = questionnaires
            };

            return View(model);
        }
    }
}