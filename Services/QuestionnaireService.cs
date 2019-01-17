using System.Collections.Generic;
using System.Threading.Tasks;
using System.Linq;
using CapiValidation.Data.Entities;
using CapiValidation.Data.Interfaces;
using CapiValidation.Services.Interfaces;
using CapiValidation.Data;

namespace CapiValidation.Services
{
    public class QuestionnaireService : IQuestionnaireService
    {
        private readonly IUnitOfWork _uow;

        public QuestionnaireService(IUnitOfWork uow)
            => _uow = uow;

        public async Task<IEnumerable<Questionnaire>> GetAllAsync()
            => (await _uow.GetPartialRepository<Questionnaire>().ListAsync()).OrderBy(q => q.Title);

        public async Task<Questionnaire> GetByIdAsync(params object[] id)
            => await _uow.GetPartialRepository<Questionnaire>().GetByIdAsync(id);

        public void Dispose()
            => _uow.Dispose();
    }
}