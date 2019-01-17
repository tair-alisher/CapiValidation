using System;
using CapiValidation.Data.Entities;

namespace CapiValidation.Services.Interfaces
{
    public interface IQuestionnaireService : IService, IReadableService<Questionnaire>, IDisposable
    {

    }
}